@extends('layouts.frontend')

@section('title', 'Order Confirmed - ' . config('app.name'))

@section('content')
<div class="max-w-2xl mx-auto px-4 py-16 text-center">

    {{-- Flash Messages --}}
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm font-medium mb-8 max-w-lg mx-auto flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif
    @if(session('message'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 text-sm font-medium mb-8 max-w-lg mx-auto flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('message') }}
        </div>
    @endif

    {{-- Success Animation --}}
    <div class="mb-8">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-3">Order Placed!</h1>
        <p class="text-neutral-600 mb-1">Thank you for shopping with Anarjo.</p>
        <p class="text-sm text-neutral-500">We've sent a confirmation to your email.</p>
    </div>

    {{-- Order Info Card --}}
    <div class="border border-neutral-200 bg-white p-6 sm:p-8 text-left mb-8">

        {{-- Invoice & Status --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-6 border-b border-neutral-200">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-neutral-400 mb-1">Invoice Number</p>
                <p class="text-lg font-bold font-mono">{{ $order->invoice_no }}</p>
            </div>
            <div class="flex items-center gap-2">
                @if($order->payment_status === 'completed')
                    <span class="inline-flex items-center gap-1.5 bg-green-50 text-green-700 border border-green-200 px-3 py-1.5 text-xs font-semibold uppercase tracking-wider rounded-full">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Payment Complete
                    </span>
                @elseif($order->payment_status === 'failed')
                    <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-700 border border-red-200 px-3 py-1.5 text-xs font-semibold uppercase tracking-wider rounded-full">
                        Payment Failed
                    </span>
                @elseif($order->payment_status === 'cancelled')
                    <span class="inline-flex items-center gap-1.5 bg-neutral-100 text-neutral-600 border border-neutral-200 px-3 py-1.5 text-xs font-semibold uppercase tracking-wider rounded-full">
                        Payment Cancelled
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 border border-amber-200 px-3 py-1.5 text-xs font-semibold uppercase tracking-wider rounded-full">
                        <svg class="w-3 h-3 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Awaiting Payment
                    </span>
                @endif
            </div>
        </div>

        {{-- Order Summary --}}
        <div class="space-y-3 text-sm mb-6">
            @foreach($order->items as $item)
                <div class="flex items-center gap-3">
                    <div class="w-10 h-14 bg-neutral-100 flex-shrink-0 flex items-center justify-center overflow-hidden">
                        @if($item->book?->cover_image)
                            <img src="{{ asset('storage/' . $item->book->cover_image) }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-neutral-300 text-xs">+</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-xs uppercase tracking-wider truncate">{{ $item->book?->title ?? 'Product' }}</p>
                        <p class="text-neutral-500 text-xs">Qty: {{ $item->qty }}</p>
                    </div>
                    <p class="font-bold text-sm">{{ setting('currency_symbol', '৳') }}{{ number_format($item->subtotal) }}</p>
                </div>
            @endforeach
        </div>

        <div class="border-t border-neutral-200 pt-4 space-y-2 text-sm">
            <div class="flex justify-between"><span class="text-neutral-600">Subtotal</span><span class="font-semibold">{{ setting('currency_symbol', '৳') }}{{ number_format($order->subtotal) }}</span></div>
            <div class="flex justify-between"><span class="text-neutral-600">Shipping</span><span class="font-semibold">{{ $order->shipping > 0 ? setting('currency_symbol', '৳') . number_format($order->shipping) : 'Free' }}</span></div>
            @if($order->discount > 0)
                <div class="flex justify-between text-green-600"><span>Discount</span><span>-{{ setting('currency_symbol', '৳') }}{{ number_format($order->discount) }}</span></div>
            @endif
            <div class="border-t border-neutral-200 pt-3 flex justify-between font-bold text-xl">
                <span>Total</span>
                <span>{{ setting('currency_symbol', '৳') }}{{ number_format($order->total) }}</span>
            </div>
        </div>

        {{-- Shipping Address --}}
        @if($order->address)
            <div class="mt-6 pt-6 border-t border-neutral-200">
                <h4 class="text-xs font-semibold uppercase tracking-widest text-neutral-400 mb-3">Shipping To</h4>
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-neutral-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <div class="text-sm text-neutral-600 leading-relaxed">
                        <span class="font-semibold text-black">{{ $order->address->name }}</span><br>
                        {{ $order->address->phone }}<br>
                        {{ $order->address->address }}, {{ $order->address->area }}<br>
                        {{ $order->address->district }}@if($order->address->postal_code) - {{ $order->address->postal_code }}@endif
                    </div>
                </div>
            </div>
        @endif

        {{-- Delivery Status --}}
        <div class="mt-6 pt-6 border-t border-neutral-200">
            <h4 class="text-xs font-semibold uppercase tracking-widest text-neutral-400 mb-3">Order Status</h4>
            <div class="flex items-center gap-6 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full {{ $order->delivery_status === 'pending' ? 'bg-amber-400 animate-pulse' : 'bg-green-500' }}"></span>
                    <span class="text-neutral-600">
                        @if($order->delivery_status === 'pending')
                            Processing your order
                        @elseif($order->delivery_status === 'processing')
                            Being prepared
                        @elseif($order->delivery_status === 'shipped')
                            On the way to you
                        @elseif($order->delivery_status === 'delivered')
                            Delivered
                        @else
                            {{ ucfirst($order->delivery_status) }}
                        @endif
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full {{ in_array($order->payment_status, ['completed']) ? 'bg-green-500' : 'bg-amber-400 animate-pulse' }}"></span>
                    <span class="text-neutral-600">
                        @if($order->payment_status === 'completed')
                            Paid via {{ ucfirst($order->payment_method) }}
                        @else
                            {{ ucfirst($order->payment_status) }}
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-col sm:flex-row items-center justify-center gap-3 mb-8">
        @php $invoiceToken = $order->user_id === null ? ['token' => $order->confirmation_token] : []; @endphp
        <a href="{{ route('order.invoice.preview', array_merge(['order' => $order], $invoiceToken)) }}" class="btn-outline w-full sm:w-auto" target="_blank">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            Preview Invoice
        </a>
        <a href="{{ route('order.invoice', array_merge(['order' => $order], $invoiceToken)) }}" class="btn-outline w-full sm:w-auto">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Download Invoice
        </a>
        <a href="{{ route('books.index') }}" class="btn-primary w-full sm:w-auto">
            Continue Shopping
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M5 12h14m-7-7l7 7-7 7"/></svg>
        </a>
    </div>

    {{-- What's Next --}}
    <div class="bg-neutral-50 border border-neutral-200 p-6 text-left">
        <h3 class="text-sm font-bold uppercase tracking-wider mb-4">What happens next?</h3>
        <div class="space-y-3 text-sm text-neutral-600">
            <div class="flex items-start gap-3">
                <span class="w-6 h-6 bg-black text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</span>
                <p>We'll review your order and start processing it shortly.</p>
            </div>
            <div class="flex items-start gap-3">
                <span class="w-6 h-6 bg-black text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</span>
                <p>You'll receive updates via email and SMS about your order status.</p>
            </div>
            <div class="flex items-start gap-3">
                <span class="w-6 h-6 bg-black text-white rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">3</span>
                <p>Your order will be delivered to your address within 3-5 business days.</p>
            </div>
        </div>
    </div>
</div>
@endsection
