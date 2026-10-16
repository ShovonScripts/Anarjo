<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\InvoiceService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::with('items')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('frontend.orders.index', compact('orders'));
    }

    public function confirmation(Order $order): View
    {
        abort_unless(Gate::forUser(auth()->user())->allows('view', $order), 404);

        return view('frontend.orders.confirmation', compact('order'));
    }

    public function invoice(Order $order, InvoiceService $invoice): Response
    {
        abort_unless(Gate::forUser(auth()->user())->allows('view', $order), 404);

        return $invoice->download($order);
    }

    public function invoicePreview(Order $order, InvoiceService $invoice): Response
    {
        abort_unless(Gate::forUser(auth()->user())->allows('view', $order), 404);

        return $invoice->stream($order);
    }
}
