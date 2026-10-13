<?php

namespace App\Livewire;

use App\Enums\DeliveryStatus;
use App\Enums\PaymentStatus;
use App\Jobs\ProcessOrderJob;
use App\Jobs\SendOrderConfirmationJob;
use App\Models\Address;
use App\Models\Book;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Stationery;
use App\Services\Cart\CartService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Checkout extends Component
{
    public string $email = '';

    public string $name = '';

    public string $phone = '';

    public string $district = '';

    public string $area = '';

    public string $address = '';

    public string $postal_code = '';

    public string $payment_method = 'cod';

    public string $coupon_code = '';

    public ?Coupon $appliedCoupon = null;

    public string $notes = '';

    public int $selected_address_id = 0;

    protected CartService $cartService;

    public function boot(CartService $cartService): void
    {
        $this->cartService = $cartService;
    }

    protected function rules(): array
    {
        $methods = ['cod'];
        if (setting('pm_bkash', '1') === '1') {
            $methods[] = 'bkash';
        }
        if (setting('pm_sslcommerz', '1') === '1') {
            $methods[] = 'sslcommerz';
        }

        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'district' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'postal_code' => 'nullable|string|max:10',
            'payment_method' => 'required|in:'.implode(',', $methods),
            'notes' => 'nullable|string|max:500',
        ];

        if (! auth()->check()) {
            $rules['email'] = 'required|email|max:255';
        }

        return $rules;
    }

    public function mount(): void
    {
        if ($this->cartService->isEmpty()) {
            $this->redirect(route('books.index'), navigate: true);

            return;
        }

        if (auth()->check()) {
            $address = Address::where('user_id', auth()->id())->latest()->first();
            if ($address) {
                $this->name = $address->name;
                $this->phone = $address->phone;
                $this->district = $address->district;
                $this->area = $address->area;
                $this->address = $address->address;
                $this->postal_code = $address->postal_code;
                $this->selected_address_id = $address->id;
            } else {
                $this->name = auth()->user()->name;
                $this->phone = auth()->user()->phone ?? '';
            }
        }
    }

    protected function isDhaka(): bool
    {
        return str_contains(strtolower($this->district), 'dhaka');
    }

    protected function getShippingRate(): int
    {
        return $this->isDhaka()
            ? (int) (setting('shipping_flat_rate_inside', '60'))
            : (int) (setting('shipping_flat_rate_outside', '100'));
    }

    public bool $couponLoading = false;

    public function applyCoupon(): void
    {
        $this->couponLoading = true;
        $this->resetErrorBag('coupon_code');

        $this->validate(['coupon_code' => 'required|string|max:50']);

        $coupon = Coupon::where('code', strtoupper(trim($this->coupon_code)))->first();

        if (! $coupon || ! $coupon->isValid()) {
            $this->addError('coupon_code', 'This coupon is invalid or has expired. Please check the code and try again.');
            $this->couponLoading = false;

            return;
        }

        $subtotal = $this->cartService->subtotal();

        if ($coupon->min_amount && $subtotal < $coupon->min_amount) {
            $this->addError('coupon_code', 'This coupon requires a minimum order of '.setting('currency_symbol', '৳').number_format($coupon->min_amount).'. Add more items to your cart.');
            $this->couponLoading = false;

            return;
        }

        $this->appliedCoupon = $coupon;
        $this->coupon_code = '';
        $this->couponLoading = false;
        $this->dispatch('notify', message: 'Coupon "'.$coupon->code.'" applied! You saved '.setting('currency_symbol', '৳').number_format($this->getDiscount()).'.');
    }

    public function removeCoupon(): void
    {
        $this->appliedCoupon = null;
        $this->coupon_code = '';
        $this->dispatch('notify', message: 'Coupon removed.');
    }

    protected function getDiscount(): int
    {
        if (! $this->appliedCoupon) {
            return 0;
        }

        $subtotal = $this->cartService->subtotal();

        return $this->appliedCoupon->type === 'percent'
            ? (int) round($subtotal * $this->appliedCoupon->value / 100)
            : (int) round($this->appliedCoupon->value);
    }

    public function placeOrder(): void
    {
        $this->validate();

        $rateLimitKey = 'checkout:'.(auth()->id() ?? request()->ip());
        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            session()->flash('error', 'Too many order attempts. Please try again in '.RateLimiter::availableIn($rateLimitKey).' seconds.');

            return;
        }
        RateLimiter::hit($rateLimitKey, 60);

        if ($this->cartService->isEmpty()) {
            session()->flash('error', 'Your cart is empty.');

            return;
        }

        // Validate availability & prices and reserve inventory in a single
        // transaction so the stock row-lock is held from check through to order.
        $order = DB::transaction(function () {
            $lines = $this->validateCartLines();

            $subtotal = round(array_sum(array_map(fn ($line) => $line['price'] * $line['qty'], $lines)), 2);

            if ($this->appliedCoupon) {
                $freshCoupon = Coupon::lockForUpdate()->find($this->appliedCoupon->id);
                if (! $freshCoupon || ! $freshCoupon->isValid()) {
                    throw ValidationException::withMessages(['coupon_code' => 'Coupon is no longer valid.']);
                }

                if ($freshCoupon->min_amount && $subtotal < $freshCoupon->min_amount) {
                    throw ValidationException::withMessages(['coupon_code' => 'Cart no longer meets the minimum order amount.']);
                }

                $this->appliedCoupon = $freshCoupon;
            }

            $flatRate = $this->getShippingRate();
            $freeThreshold = (int) (setting('shipping_free_threshold', '500'));
            $shipping = $subtotal >= $freeThreshold ? 0 : $flatRate;
            $discount = $this->getDiscountFromSubtotal($subtotal);
            $total = max(0, round($subtotal + $shipping - $discount, 2));

            $invoice = 'INV-'.date('Ymd').'-'.strtoupper(Str::random(6));

            $address = Address::create([
                'user_id' => auth()->id(),
                'name' => $this->name,
                'phone' => $this->phone,
                'district' => $this->district,
                'area' => $this->area,
                'address' => $this->address,
                'postal_code' => $this->postal_code,
            ]);

            $order = Order::create([
                'user_id' => auth()->id(),
                'guest_email' => auth()->check() ? null : $this->email,
                'invoice_no' => $invoice,
                'subtotal' => $subtotal,
                'shipping' => $shipping,
                'discount' => $discount,
                'total' => $total,
                'payment_method' => $this->payment_method,
                'payment_status' => PaymentStatus::Pending->value,
                'delivery_status' => DeliveryStatus::Pending->value,
                'coupon_id' => $this->appliedCoupon?->id,
                'address_id' => $address->id,
                'notes' => $this->notes,
            ]);

            foreach ($lines as $line) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $line['id'],
                    'item_type' => $line['type'],
                    'price' => $line['price'],
                    'qty' => $line['qty'],
                    'subtotal' => round($line['price'] * $line['qty'], 2),
                ]);

                // Reserve stock atomically while the order is being created.
                $this->decrementStock($line);
            }

            if ($this->appliedCoupon) {
                $this->appliedCoupon->increment('usage_count');
            }

            return $order;
        });

        $this->cartService->clear();

        ProcessOrderJob::dispatch($order);
        SendOrderConfirmationJob::dispatch($order);

        if ($this->payment_method === 'cod') {
            session()->flash('order_success', $order->id);
            $this->redirect($order->confirmationUrl(), navigate: true);
        } else {
            session()->flash('order_id', $order->id);
            $this->redirect($order->paymentUrl(), navigate: true);
        }
    }

    private function validateCartLines(): array
    {
        $lines = [];

        foreach ($this->cartService->get() as $item) {
            $model = ($item['type'] ?? 'book') === 'stationery' ? Stationery::class : Book::class;

            $product = $model::lockForUpdate()->find($item['id'] ?? 0);

            if (! $product || $product->status !== 'published') {
                throw ValidationException::withMessages([
                    'cart' => 'An item in your cart is no longer available for purchase.',
                ]);
            }

            $qty = max(1, min(99, (int) ($item['qty'] ?? 1)));

            if (! $product->pre_order && $qty > (int) $product->stock) {
                throw ValidationException::withMessages([
                    'cart' => "Only {$product->stock} left in stock for \"{$product->title}\".",
                ]);
            }

            $lines[] = [
                'id' => $product->id,
                'type' => ($item['type'] ?? 'book') === 'stationery' ? 'stationery' : 'book',
                'title' => $product->title,
                'price' => (float) ($product->sale_price ?? $product->price),
                'qty' => $qty,
            ];
        }

        return $lines;
    }

    private function decrementStock(array $line): void
    {
        $model = $line['type'] === 'stationery' ? Stationery::find($line['id']) : Book::find($line['id']);

        if (! $model || $model->pre_order) {
            return;
        }

        $model->decrement('stock', $line['qty']);
    }

    private function getDiscountFromSubtotal(float $subtotal): int
    {
        if (! $this->appliedCoupon) {
            return 0;
        }

        return $this->appliedCoupon->type === 'percent'
            ? (int) round($subtotal * $this->appliedCoupon->value / 100)
            : (int) round($this->appliedCoupon->value);
    }

    public function getEnabledPaymentMethods(): array
    {
        $methods = [
            [
                'id' => 'cod',
                'label' => 'Cash on Delivery',
                'description' => 'Pay when your order arrives',
                'icon' => 'cash',
            ],
        ];
        if (setting('pm_bkash', '1') === '1') {
            $methods[] = [
                'id' => 'bkash',
                'label' => 'bKash',
                'description' => 'Pay securely via bKash',
                'icon' => 'bkash',
            ];
        }
        if (setting('pm_sslcommerz', '1') === '1') {
            $methods[] = [
                'id' => 'sslcommerz',
                'label' => 'Card / Online Banking',
                'description' => 'Visa, Mastercard, Nagad & more',
                'icon' => 'card',
            ];
        }

        return $methods;
    }

    public function render()
    {
        $items = $this->cartService->get();
        $subtotal = $this->cartService->subtotal();
        $flatRate = $this->getShippingRate();
        $freeThreshold = (int) (setting('shipping_free_threshold', '500'));
        $shipping = $subtotal >= $freeThreshold ? 0 : $flatRate;
        $discount = $this->getDiscount();
        $total = max(0, $subtotal + $shipping - $discount);
        $paymentMethods = $this->getEnabledPaymentMethods();
        $currency = setting('currency_symbol', '৳');

        return view('livewire.checkout', compact('items', 'subtotal', 'shipping', 'discount', 'total', 'paymentMethods', 'currency'))
            ->layout('layouts.frontend');
    }
}
