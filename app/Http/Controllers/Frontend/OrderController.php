<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('frontend.orders.index', compact('orders'));
    }

    public function confirmation(Order $order)
    {
        if ($order->user_id && $order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('frontend.orders.confirmation', compact('order'));
    }
}
