@extends('layouts.frontend')

@section('title', 'My Orders - ' . config('app.name'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="section-heading mb-12">My Orders</h1>

    @if($orders->count())
        <div class="divide-y divide-neutral-200 border border-neutral-200 bg-white">
            @foreach($orders as $order)
                <div class="p-6 hover:bg-neutral-50 transition-colors">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-3">
                        <div>
                            <p class="text-xs text-neutral-500 mb-1">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                            <p class="font-semibold font-mono text-sm">{{ $order->invoice_no }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            @if($order->payment_status === 'completed')
                                <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 border border-green-200 px-2 py-1 text-xs font-semibold uppercase tracking-wider rounded-full">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Paid
                                </span>
                            @elseif($order->payment_status === 'pending')
                                <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 border border-amber-200 px-2 py-1 text-xs font-semibold uppercase tracking-wider rounded-full">
                                    Pending
                                </span>
                            @elseif($order->payment_status === 'failed')
                                <span class="inline-flex items-center gap-1 bg-red-50 text-red-700 border border-red-200 px-2 py-1 text-xs font-semibold uppercase tracking-wider rounded-full">
                                    Failed
                                </span>
                            @endif
                            <p class="font-bold text-lg">{{ setting('currency_symbol', '৳') }}{{ number_format($order->total) }}</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2 mb-3">
                        @foreach($order->items->take(3) as $item)
                            <span class="text-xs bg-neutral-100 text-neutral-600 px-2.5 py-1 rounded">{{ $item->book?->title ?? 'Product' }} ×{{ $item->qty }}</span>
                        @endforeach
                        @if($order->items->count() > 3)
                            <span class="text-xs bg-neutral-100 text-neutral-500 px-2.5 py-1 rounded">+{{ $order->items->count() - 3 }} more</span>
                        @endif
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mt-4 pt-3 border-t border-neutral-100">
                        <div class="flex items-center gap-2 text-xs text-neutral-500">
                            <span class="w-1.5 h-1.5 rounded-full {{ $order->delivery_status === 'delivered' ? 'bg-green-500' : ($order->delivery_status === 'cancelled' ? 'bg-red-500' : 'bg-amber-400') }}"></span>
                            @if($order->delivery_status === 'pending')
                                Processing
                            @elseif($order->delivery_status === 'processing')
                                Being prepared
                            @elseif($order->delivery_status === 'shipped')
                                On the way
                            @elseif($order->delivery_status === 'delivered')
                                Delivered
                            @else
                                {{ ucfirst($order->delivery_status) }}
                            @endif
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('order.confirmation', $order) }}" class="text-sm font-semibold text-black hover:text-brand-600 transition-colors">View Details</a>
                            <a href="{{ route('order.invoice.preview', $order) }}" class="text-sm font-medium text-neutral-500 hover:text-black transition-colors" target="_blank">Invoice</a>
                            <a href="{{ route('order.invoice', $order) }}" class="text-sm font-medium text-neutral-500 hover:text-black transition-colors">Download</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @else
        <div class="text-center py-20 border border-neutral-200 bg-white">
            <svg class="w-16 h-16 mx-auto mb-5 text-neutral-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <h3 class="text-xl font-bold mb-2">No orders yet</h3>
            <p class="text-neutral-500 mb-6">When you place an order, it will appear here.</p>
            <a href="{{ route('books.index') }}" class="btn-primary">Browse Books</a>
        </div>
    @endif
</div>
@endsection
