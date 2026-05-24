<?php

namespace App\Livewire;

use App\Jobs\ProcessOrderJob;
use App\Jobs\SendOrderConfirmationJob;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Cart\CartService;
use Livewire\Component;
use Illuminate\Support\Str;

class Checkout extends Component
{
    public string $name = '';
    public string $phone = '';
    public string $district = '';
    public string $area = '';
    public string $address = '';
    public string $postal_code = '';
    public string $payment_method = 'cod';
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

        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'district' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'postal_code' => 'nullable|string|max:10',
            'payment_method' => 'required|in:' . implode(',', $methods),
            'notes' => 'nullable|string|max:500',
        ];
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

    public function placeOrder(): void
    {
        $this->validate();

        if ($this->cartService->isEmpty()) {
            session()->flash('error', 'Your cart is empty.');
            return;
        }

        $items = $this->cartService->get();
        $subtotal = $this->cartService->subtotal();

        $flatRate = $this->getShippingRate();
        $freeThreshold = (int) (setting('shipping_free_threshold', '500'));
        $shipping = $subtotal >= $freeThreshold ? 0 : $flatRate;
        $total = $subtotal + $shipping;

        $invoice = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6));

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
            'invoice_no' => $invoice,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'discount' => 0,
            'total' => $total,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_method === 'cod' ? 'pending' : 'pending',
            'delivery_status' => 'pending',
            'address_id' => $address->id,
            'notes' => $this->notes,
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'book_id' => $item['id'],
                'price' => $item['price'],
                'qty' => $item['qty'],
                'subtotal' => $item['price'] * $item['qty'],
            ]);
        }

        $this->cartService->clear();

        ProcessOrderJob::dispatch($order);
        SendOrderConfirmationJob::dispatch($order);

        if ($this->payment_method === 'cod') {
            session()->flash('order_success', $order->id);
            $this->redirect(route('order.confirmation', $order->id), navigate: true);
        } else {
            session()->flash('order_id', $order->id);
            $this->redirect(route('payment.process', $order->id), navigate: true);
        }
    }

    public function getEnabledPaymentMethods(): array
    {
        $methods = [['id' => 'cod', 'label' => 'Cash on Delivery']];
        if (setting('pm_bkash', '1') === '1') {
            $methods[] = ['id' => 'bkash', 'label' => 'bKash'];
        }
        if (setting('pm_sslcommerz', '1') === '1') {
            $methods[] = ['id' => 'sslcommerz', 'label' => 'SSLCommerz'];
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
        $total = $subtotal + $shipping;
        $paymentMethods = $this->getEnabledPaymentMethods();

        return view('livewire.checkout', compact('items', 'subtotal', 'shipping', 'total', 'paymentMethods'))
            ->layout('layouts.frontend');
    }
}
