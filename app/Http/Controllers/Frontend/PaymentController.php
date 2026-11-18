<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function process(Order $order): RedirectResponse|View
    {
        abort_unless(Gate::forUser(auth()->user())->allows('pay', $order), 403);

        if ($order->payment_method === 'cod') {
            return redirect()->to($order->confirmationUrl());
        }

        return view('frontend.orders.payment', compact('order'));
    }
}
