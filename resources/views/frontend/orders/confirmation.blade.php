@extends('layouts.frontend')

@section('title', 'Order Confirmed - ' . config('app.name'))

@section('content')
<div class="max-w-2xl mx-auto px-4 py-20 text-center">
    <p class="text-6xl mb-6 text-neutral-300">+</p>
    <h1 class="text-4xl font-extrabold tracking-tight mb-4">Order Placed!</h1>
    <p class="text-neutral-600 mb-2">Thank you for your order.</p>
    <p class="text-lg font-semibold mb-10">Invoice: {{ $order->invoice_no }}</p>

    <div class="border border-neutral-200 p-8 text-left mb-10">
        <h3 class="text-sm font-semibold uppercase tracking-widest mb-6">Order Summary</h3>
        <div class="space-y-3 text-sm">
            <div class="flex justify-between"><span class="text-neutral-600">Subtotal</span><span class="font-semibold">৳{{ number_format($order->subtotal) }}</span></div>
            <div class="flex justify-between"><span class="text-neutral-600">Shipping</span><span class="font-semibold">৳{{ number_format($order->shipping) }}</span></div>
            <div class="flex justify-between"><span class="text-neutral-600">Discount</span><span class="font-semibold">৳{{ number_format($order->discount) }}</span></div>
            <div class="border-t border-neutral-200 pt-3 flex justify-between font-bold text-lg">
                <span>Total</span>
                <span>৳{{ number_format($order->total) }}</span>
            </div>
        </div>

        @if($order->address)
            <div class="mt-6 pt-6 border-t border-neutral-200">
                <h4 class="text-xs font-semibold uppercase tracking-widest mb-3">Shipping To</h4>
                <p class="text-sm text-neutral-600 leading-relaxed">
                    {{ $order->address->name }}<br>
                    {{ $order->address->phone }}<br>
                    {{ $order->address->address }}, {{ $order->address->area }}<br>
                    {{ $order->address->district }} - {{ $order->address->postal_code }}
                </p>
            </div>
        @endif

        <div class="mt-6 pt-6 border-t border-neutral-200 space-y-2">
            <p class="text-sm text-neutral-600">Payment: <span class="font-semibold uppercase">{{ ucfirst($order->payment_method) }}</span> — <span class="font-semibold {{ $order->payment_status === 'completed' ? 'text-brand-600' : 'text-neutral-600' }}">{{ ucfirst($order->payment_status) }}</span></p>
            <p class="text-sm text-neutral-600">Delivery: <span class="font-semibold uppercase">{{ ucfirst($order->delivery_status) }}</span></p>
        </div>
    </div>

    <a href="{{ route('books.index') }}" class="btn-primary">Continue Shopping</a>
</div>
@endsection
