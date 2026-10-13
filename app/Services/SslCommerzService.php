<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Models\Order;
use Illuminate\Support\Facades\Http;

class SslCommerzService
{
    protected string $storeId;

    protected string $storePassword;

    protected bool $sandbox;

    protected string $apiUrl;

    protected string $validationUrl;

    public function __construct()
    {
        $this->storeId = config('payment.sslcommerz.store_id');
        $this->storePassword = config('payment.sslcommerz.store_passwd');
        $this->sandbox = setting('payment_sslcommerz_sandbox', '1') === '1';

        $this->apiUrl = $this->sandbox
            ? 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php'
            : 'https://securepay.sslcommerz.com/gwprocess/v4/api.php';

        $this->validationUrl = $this->sandbox
            ? 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php'
            : 'https://securepay.sslcommerz.com/validator/api/validationserverAPI.php';
    }

    public function isConfigured(): bool
    {
        return ! empty($this->storeId) && ! empty($this->storePassword);
    }

    public function initiate(Order $order): array
    {
        $items = $order->items;
        $productName = $items->map(fn ($i) => $i->book?->title ?? 'Book')->join(', ');

        $postData = [
            'store_id' => $this->storeId,
            'store_passwd' => $this->storePassword,
            'total_amount' => (string) $order->total,
            'currency' => setting('currency_code', 'BDT'),
            'tran_id' => $order->invoice_no,
            'success_url' => route('payment.sslcommerz.success', [], true),
            'fail_url' => route('payment.sslcommerz.fail', [], true),
            'cancel_url' => route('payment.sslcommerz.cancel', [], true),
            'ipn_url' => route('payment.sslcommerz.ipn', [], true),
            'cus_name' => $order->address?->name ?? $order->user?->name ?? 'Customer',
            'cus_email' => $order->user?->email ?? $order->guest_email ?? 'customer@example.com',
            'cus_phone' => $order->address?->phone ?? '01XXXXXXX',
            'cus_add1' => $order->address?->address ?? 'Dhaka',
            'cus_city' => $order->address?->district ?? 'Dhaka',
            'cus_postcode' => $order->address?->postal_code ?? '1200',
            'cus_country' => 'Bangladesh',
            'shipping_method' => 'YES',
            'num_of_item' => (string) $items->sum('qty'),
            'product_name' => $productName ?: 'Books and stationery',
            'product_category' => 'Books',
            'product_profile' => 'general',
        ];

        $response = Http::asForm()->post($this->apiUrl, $postData);

        $result = $response->json();

        if (! $result || ! isset($result['status'])) {
            return ['status' => 'FAILED', 'message' => 'No response from SSLCommerz.'];
        }

        return $result;
    }

    public function validateTranId(string $tranId, string $amount, string $currency, ?string $valId = null): bool
    {
        $order = Order::where('invoice_no', $tranId)->first();

        if (! $order || $order->payment_status !== PaymentStatus::Pending->value) {
            return false;
        }

        if (round((float) $order->total, 2) !== round((float) $amount, 2)) {
            return false;
        }

        if (strtoupper($currency) !== strtoupper((string) setting('currency_code', 'BDT'))) {
            return false;
        }

        // 1. Primary validation: If val_id is provided, query SSLCommerz Order Validation API via GET
        if (! empty($valId)) {
            $response = Http::get($this->validationUrl, [
                'val_id' => $valId,
                'store_id' => $this->storeId,
                'store_passwd' => $this->storePassword,
                'v' => '1',
                'format' => 'json',
            ]);

            $result = $response->json();

            if ($result && in_array($result['status'] ?? '', ['VALID', 'VALIDATED'], true)) {
                $returnedTranId = $result['tran_id'] ?? '';
                $returnedAmount = (float) ($result['amount'] ?? 0);

                if ($returnedTranId === $tranId && round($returnedAmount, 2) === round((float) $order->total, 2)) {
                    return true;
                }
            }
        }

        // 2. Secondary fallback validation if val_id is not passed
        $response = Http::get($this->validationUrl, [
            'store_id' => $this->storeId,
            'store_passwd' => $this->storePassword,
            'tran_id' => $tranId,
            'v' => '1',
            'format' => 'json',
        ]);

        $result = $response->json();

        return $result && in_array($result['status'] ?? '', ['VALID', 'VALIDATED'], true);
    }
}
