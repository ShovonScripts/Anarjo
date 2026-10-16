<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class BkashService
{
    protected string $appKey;

    protected string $appSecret;

    protected string $username;

    protected string $password;

    protected bool $sandbox;

    protected string $baseUrl;

    protected ?string $token = null;

    public function __construct()
    {
        $this->appKey = config('payment.bkash.app_key');
        $this->appSecret = config('payment.bkash.app_secret');
        $this->username = config('payment.bkash.username');
        $this->password = config('payment.bkash.password');
        $this->sandbox = setting('payment_bkash_sandbox', '1') === '1';

        $this->baseUrl = $this->sandbox
            ? 'https://tokenized.sandbox.bka.sh/v1.2.0-beta'
            : 'https://tokenized.pay.bka.sh/v1.2.0-beta';
    }

    public function isConfigured(): bool
    {
        return ! empty($this->appKey) && ! empty($this->appSecret);
    }

    public function grantToken(): string
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'username' => $this->username,
            'password' => $this->password,
        ])->post($this->baseUrl.'/tokenized/checkout/token/grant', [
            'app_key' => $this->appKey,
            'app_secret' => $this->appSecret,
        ]);

        $result = $response->json();

        if (! $result || empty($result['id_token'])) {
            throw new \Exception('Failed to grant bKash token: '.($result['message'] ?? 'Unknown error'));
        }

        $this->token = $result['id_token'];

        return $this->token;
    }

    public function createPayment(Order $order): array
    {
        $token = $this->token ?? $this->grantToken();

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$token,
            'X-APP-Key' => $this->appKey,
        ])->post($this->baseUrl.'/tokenized/checkout/create', [
            'mode' => '0011',
            'payerReference' => (string) ($order->user_id ?? 'guest'),
            'callbackURL' => route('payment.bkash.callback', [], true),
            'amount' => (string) $order->total,
            'currency' => 'BDT',
            'intent' => 'sale',
            'merchantInvoiceNumber' => $order->invoice_no,
        ]);

        $result = $response->json();

        if (! $result || empty($result['bkashURL'])) {
            throw new \Exception('Failed to create bKash payment: '.($result['message'] ?? 'Unknown error'));
        }

        // Store the bKash paymentID on the order so we can look it up in the callback.
        $order->update(['payment_id' => $result['paymentID'] ?? null]);

        return $result;
    }

    public function executePayment(string $paymentID): array
    {
        $token = $this->token ?? $this->grantToken();

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$token,
            'X-APP-Key' => $this->appKey,
        ])->post($this->baseUrl.'/tokenized/checkout/execute', [
            'paymentID' => $paymentID,
        ]);

        $result = $response->json();

        if (! $result) {
            throw new \Exception('Failed to execute bKash payment.');
        }

        return $result;
    }
}
