@extends('layouts.frontend')

@section('title', 'My Orders - ' . config('app.name'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="section-heading mb-12">My Orders</h1>

    @if($orders->count())
        <div class="divide-y divide-neutral-200 border border-neutral-200">
            @foreach($orders as $order)
                <div class="p-6 hover:bg-neutral-50 transition-colors">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="text-xs text-neutral-500 mb-1">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                            <p class="font-semibold">Invoice: {{ $order->invoice_no }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-lg">৳{{ number_format($order->total) }}</p>
                            <p class="text-xs uppercase tracking-wider font-semibold {{ $order->delivery_status === 'delivered' ? 'text-brand-600' : ($order->delivery_status === 'cancelled' ? 'text-red-600' : 'text-neutral-500') }}">{{ $order->delivery_status }}</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 mb-3">
                        @foreach($order->items as $item)
                            <span class="text-xs bg-neutral-100 px-2 py-1">{{ $item->title }} x{{ $item->quantity }}</span>
                        @endforeach
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('order.confirmation', $order) }}" class="text-sm font-medium text-brand-600 hover:text-black transition-colors">View Details</a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @else
        <div class="text-center py-20 border border-neutral-200">
            <svg class="w-12 h-12 mx-auto mb-4 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="text-neutral-500 mb-4">You haven't placed any orders yet.</p>
            <a href="{{ route('books.index') }}" class="btn-primary btn-sm">Browse Books</a>
        </div>
    @endif
</div>
@endsection
