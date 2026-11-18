<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\SslCommerzCallbackRequest;
use App\Models\Order;
use App\Services\SslCommerzService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class SslCommerzController extends Controller
{
    public function __construct(
        protected SslCommerzService $sslCommerz
    ) {}

    public function initiate(Order $order): RedirectResponse
    {
        abort_unless(Gate::forUser(auth()->user())->allows('pay', $order), 403);

        if ($order->payment_status !== PaymentStatus::Pending->value) {
            return redirect()->to($order->confirmationUrl());
        }

        if (! $this->sslCommerz->isConfigured()) {
            return redirect()->to($order->paymentUrl())
                ->with('error', 'SSLCommerz is not configured yet.');
        }

        $result = $this->sslCommerz->initiate($order);

        if (($result['status'] ?? '') === 'success' && ! empty($result['GatewayPageURL'])) {
            return redirect()->away($result['GatewayPageURL']);
        }

        $message = $result['message'] ?? 'Failed to initiate payment. Please try again.';

        return redirect()->to($order->paymentUrl())
            ->with('error', $message);
    }

    public function success(SslCommerzCallbackRequest $request): RedirectResponse
    {
        $order = Order::where('invoice_no', $request->input('tran_id'))->first();

        if (! $order) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }

        // The IPN may already have marked this order complete (SSLCommerz sends
        // IPN before redirecting the customer). Never downgrade a paid order.
        if ($order->payment_status === PaymentStatus::Completed->value) {
            return redirect()->to($order->confirmationUrl())
                ->with('message', 'Payment successful!');
        }

        $amount = (string) ($request->input('amount') ?? $order->total);
        $currency = (string) ($request->input('currency') ?? setting('currency_code', 'BDT'));
        $valId = $request->input('val_id');

        $isValid = $this->sslCommerz->validateTranId(
            $request->input('tran_id'),
            $amount,
            $currency,
            $valId
        );

        if ($isValid) {
            $order->update([
                'payment_status' => PaymentStatus::Completed->value,
                'payment_id' => $valId ?? $request->input('bank_tran_id') ?? $order->payment_id,
            ]);

            return redirect()->to($order->confirmationUrl())
                ->with('message', 'Payment successful!');
        }

        if ($order->payment_status === PaymentStatus::Pending->value) {
            $order->update([
                'payment_status' => PaymentStatus::Failed->value,
            ]);
        }

        return redirect()->to($order->confirmationUrl())
            ->with('error', 'Payment validation failed.');
    }

    public function cancel(SslCommerzCallbackRequest $request): RedirectResponse
    {
        $order = Order::where('invoice_no', $request->input('tran_id'))->first();

        if ($order && $order->payment_status === PaymentStatus::Pending->value) {
            $order->update(['payment_status' => PaymentStatus::Cancelled->value]);
        }

        if ($order) {
            return redirect()->to($order->confirmationUrl())
                ->with('error', 'Payment was cancelled.');
        }

        return redirect()->route('home')->with('error', 'Payment was cancelled.');
    }

    public function fail(SslCommerzCallbackRequest $request): RedirectResponse
    {
        $order = Order::where('invoice_no', $request->input('tran_id'))->first();

        if ($order && $order->payment_status === PaymentStatus::Pending->value) {
            $order->update(['payment_status' => PaymentStatus::Failed->value]);
        }

        if ($order) {
            return redirect()->to($order->confirmationUrl())
                ->with('error', 'Payment failed. Please try again.');
        }

        return redirect()->route('home')->with('error', 'Payment failed. Please try again.');
    }

    public function ipn(SslCommerzCallbackRequest $request): Response
    {
        $order = Order::where('invoice_no', $request->input('tran_id'))->first();

        if (! $order || $order->payment_status === PaymentStatus::Completed->value) {
            return response('OK', 200);
        }

        $amount = (string) ($request->input('amount') ?? $order->total);
        $currency = (string) ($request->input('currency') ?? setting('currency_code', 'BDT'));
        $valId = $request->input('val_id');

        $isValid = $this->sslCommerz->validateTranId(
            $request->input('tran_id'),
            $amount,
            $currency,
            $valId
        );

        if ($isValid) {
            $order->update([
                'payment_status' => PaymentStatus::Completed->value,
                'payment_id' => $valId ?? $request->input('bank_tran_id') ?? $order->payment_id,
            ]);
        } else {
            // IPN validation failed — mark as failed so stock is restored.
            if ($order->payment_status === PaymentStatus::Pending->value) {
                $order->update(['payment_status' => PaymentStatus::Failed->value]);
            }
        }

        return response('OK', 200);
    }
}
