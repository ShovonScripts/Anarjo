<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\BkashService;
use Illuminate\Http\Request;

class BkashController extends Controller
{
    public function __construct(
        protected BkashService $bkash
    ) {}

    public function initiate(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->payment_status !== 'pending') {
            return redirect()->route('order.confirmation', $order);
        }

        if (! $this->bkash->isConfigured()) {
            return back()->with('error', 'bKash is not configured yet.');
        }

        try {
            $result = $this->bkash->createPayment($order);

            if (! empty($result['bkashURL'])) {
                return redirect()->away($result['bkashURL']);
            }

            return redirect()->route('payment.process', $order)
                ->with('error', $result['message'] ?? 'Failed to initiate bKash payment.');
        } catch (\Exception $e) {
            return redirect()->route('payment.process', $order)
                ->with('error', $e->getMessage());
        }
    }

    public function callback(Request $request)
    {
        $paymentID = $request->input('paymentID');
        $status = $request->input('status');

        if (! $paymentID) {
            return redirect()->route('home')->with('error', 'Invalid payment response.');
        }

        try {
            if ($status === 'success') {
                $result = $this->bkash->executePayment($paymentID);

                $order = Order::where('invoice_no', $result['merchantInvoiceNumber'] ?? '')->first();

                if ($order && ($result['transactionStatus'] ?? '') === 'Completed') {
                    $order->update(['payment_status' => 'completed']);

                    return redirect()->route('order.confirmation', $order)
                        ->with('message', 'Payment successful!');
                }

                if ($order) {
                    $order->update(['payment_status' => 'failed']);
                }

                return redirect()->route('checkout')
                    ->with('error', 'Payment execution failed.');
            }

            if ($paymentID) {
                $result = $this->bkash->queryPayment($paymentID);
                $order = Order::where('invoice_no', $result['merchantInvoiceNumber'] ?? '')->first();

                if ($order) {
                    $order->update(['payment_status' => 'cancelled']);
                }
            }

            return redirect()->route('checkout')
                ->with('error', 'Payment was cancelled.');
        } catch (\Exception $e) {
            return redirect()->route('checkout')
                ->with('error', $e->getMessage());
        }
    }
}
