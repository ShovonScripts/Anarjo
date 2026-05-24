<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function process(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->payment_method === 'cod') {
            return redirect()->route('order.confirmation', $order);
        }

        return view('frontend.orders.payment', compact('order'));
    }

    public function success(Request $request)
    {
        $order = Order::where('invoice_no', $request->invoice_no)->firstOrFail();
        $order->update(['payment_status' => 'completed']);

        return redirect()->route('order.confirmation', $order)
            ->with('message', 'Payment successful!');
    }

    public function cancel(Request $request)
    {
        $order = Order::where('invoice_no', $request->invoice_no)->firstOrFail();

        return redirect()->route('checkout')
            ->with('error', 'Payment was cancelled.');
    }

    public function fail(Request $request)
    {
        $order = Order::where('invoice_no', $request->invoice_no)->firstOrFail();

        return redirect()->route('checkout')
            ->with('error', 'Payment failed. Please try again.');
    }
}
