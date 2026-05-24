<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\SslCommerzService;
use Illuminate\Http\Request;

class SslCommerzController extends Controller
{
    public function __construct(
        protected SslCommerzService $sslCommerz
    ) {}

    public function initiate(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->payment_status !== 'pending') {
            return redirect()->route('order.confirmation', $order);
        }

        if (! $this->sslCommerz->isConfigured()) {
            return back()->with('error', 'SSLCommerz is not configured yet.');
        }

        $result = $this->sslCommerz->initiate($order);

        if (($result['status'] ?? '') === 'success' && ! empty($result['GatewayPageURL'])) {
            return redirect()->away($result['GatewayPageURL']);
        }

        $message = $result['message'] ?? 'Failed to initiate payment. Please try again.';
        return redirect()->route('payment.process', $order)
            ->with('error', $message);
    }

    public function success(Request $request)
    {
        $order = Order::where('invoice_no', $request->input('tran_id'))->first();

        if (! $order) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }

        $isValid = $this->sslCommerz->validateTranId(
            $request->input('tran_id'),
            $request->input('amount'),
            $request->input('currency')
        );

        if ($isValid) {
            $order->update([
                'payment_status' => 'completed',
            ]);

            return redirect()->route('order.confirmation', $order)
                ->with('message', 'Payment successful!');
        }

        $order->update([
            'payment_status' => 'failed',
        ]);

        return redirect()->route('checkout')
            ->with('error', 'Payment validation failed.');
    }

    public function cancel(Request $request)
    {
        $order = Order::where('invoice_no', $request->input('tran_id'))->first();

        if ($order) {
            $order->update(['payment_status' => 'cancelled']);
        }

        return redirect()->route('checkout')
            ->with('error', 'Payment was cancelled.');
    }

    public function fail(Request $request)
    {
        $order = Order::where('invoice_no', $request->input('tran_id'))->first();

        if ($order) {
            $order->update(['payment_status' => 'failed']);
        }

        return redirect()->route('checkout')
            ->with('error', 'Payment failed. Please try again.');
    }

    public function ipn(Request $request)
    {
        $order = Order::where('invoice_no', $request->input('tran_id'))->first();

        if (! $order || $order->payment_status === 'completed') {
            return response('OK', 200);
        }

        $isValid = $this->sslCommerz->validateTranId(
            $request->input('tran_id'),
            $request->input('amount'),
            $request->input('currency')
        );

        if ($isValid) {
            $order->update(['payment_status' => 'completed']);
        }

        return response('OK', 200);
    }
}
