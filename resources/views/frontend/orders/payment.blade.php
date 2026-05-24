@extends('layouts.frontend')

@section('title', 'Payment - ' . config('app.name'))

@section('content')
<div class="max-w-lg mx-auto px-4 py-20 text-center">
    <p class="text-6xl mb-6 text-neutral-300">+</p>
    <h1 class="text-3xl font-extrabold tracking-tight mb-4">Complete Payment</h1>
    <p class="text-neutral-600 mb-8">Invoice: <span class="font-semibold">{{ $order->invoice_no }}</span> | Total: <span class="font-bold">৳{{ number_format($order->total) }}</span></p>

    @if($order->payment_method === 'bkash')
        <div class="border border-neutral-200 p-6 mb-8 text-left">
            <h3 class="text-sm font-semibold uppercase tracking-widest mb-3">Pay with bKash</h3>
            <p class="text-sm text-neutral-500 mb-6">You will be redirected to bKash secure payment gateway.</p>
            <form method="POST" action="{{ route('payment.bkash.initiate', $order) }}">
                @csrf
                <button type="submit" class="btn-primary w-full justify-center">Pay with bKash</button>
            </form>
        </div>
    @elseif($order->payment_method === 'sslcommerz')
        <div class="border border-neutral-200 p-6 mb-8 text-left">
            <h3 class="text-sm font-semibold uppercase tracking-widest mb-3">Pay with SSLCommerz</h3>
            <p class="text-sm text-neutral-500 mb-6">You will be redirected to SSLCommerz secure payment gateway.</p>
            <form method="POST" action="{{ route('payment.sslcommerz.initiate', $order) }}">
                @csrf
                <button type="submit" class="btn-primary w-full justify-center">Pay Now — {{ setting('currency_symbol', '৳') }}{{ number_format($order->total) }}</button>
            </form>
        </div>
    @endif
</div>
@endsection
