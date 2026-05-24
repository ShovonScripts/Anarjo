<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class SslCommerzService
{
    protected string $storeId;
    protected string $storePassword;
    protected bool $sandbox;
    protected string $apiUrl;

    public function __construct()
    {
        $this->storeId = setting('payment_sslcommerz_store_id', '');
        $this->storePassword = setting('payment_sslcommerz_store_passwd', '');
        $this->sandbox = setting('payment_sslcommerz_sandbox', '1') === '1';

        $this->apiUrl = $this->sandbox
            ? 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php'
            : 'https://secure.sslcommerz.com/gwprocess/v4/api.php';
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
            'cus_email' => $order->user?->email ?? 'customer@example.com',
            'cus_phone' => $order->address?->phone ?? '01XXXXXXX',
            'cus_add1' => $order->address?->address ?? 'Dhaka',
            'cus_city' => $order->address?->district ?? 'Dhaka',
            'cus_postcode' => $order->address?->postal_code ?? '1200',
            'cus_country' => 'Bangladesh',
            'shipping_method' => 'YES',
            'num_of_item' => (string) $items->sum('qty'),
            'product_name' => $productName,
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

    public function validateTranId(string $tranId, string $amount, string $currency): bool
    {
        if ($this->sandbox) {
            return true;
        }

        $response = Http::asForm()->post($this->apiUrl, [
            'store_id' => $this->storeId,
            'store_passwd' => $this->storePassword,
            'valid_tran_id' => $tranId,
            'amount' => $amount,
            'currency' => $currency,
        ]);

        $result = $response->json();

        return ($result['status'] ?? '') === 'VALID';
    }
}
