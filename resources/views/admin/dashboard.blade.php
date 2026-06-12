@extends('layouts.admin')

@section('title', 'Dashboard - Admin - ' . config('app.name'))

@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-10">
    <div class="border border-neutral-200 bg-white p-5 hover:border-black transition-colors duration-200 group">
        <div class="flex items-start justify-between mb-3">
            <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500">Revenue</p>
            <span class="w-8 h-8 rounded-lg bg-black flex items-center justify-center text-white group-hover:bg-brand-600 transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </span>
        </div>
        <p class="text-2xl font-extrabold tracking-tight">৳{{ number_format($stats['revenue'] ?? 0) }}</p>
    </div>
    <div class="border border-neutral-200 bg-white p-5 hover:border-black transition-colors duration-200 group">
        <div class="flex items-start justify-between mb-3">
            <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500">Orders</p>
            <span class="w-8 h-8 rounded-lg bg-black flex items-center justify-center text-white group-hover:bg-brand-600 transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </span>
        </div>
        <p class="text-2xl font-extrabold tracking-tight">{{ $stats['total_orders'] ?? 0 }}</p>
        @if(($stats['pending_orders'] ?? 0) > 0)
            <p class="text-xs text-brand-600 mt-1">{{ $stats['pending_orders'] }} pending</p>
        @endif
    </div>
    <div class="border border-neutral-200 bg-white p-5 hover:border-black transition-colors duration-200 group">
        <div class="flex items-start justify-between mb-3">
            <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500">Books</p>
            <span class="w-8 h-8 rounded-lg bg-black flex items-center justify-center text-white group-hover:bg-brand-600 transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </span>
        </div>
        <p class="text-2xl font-extrabold tracking-tight">{{ $stats['total_books'] ?? 0 }}</p>
    </div>
    <div class="border border-neutral-200 bg-white p-5 hover:border-black transition-colors duration-200 group">
        <div class="flex items-start justify-between mb-3">
            <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500">Reviews</p>
            <span class="w-8 h-8 rounded-lg bg-black flex items-center justify-center text-white group-hover:bg-brand-600 transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            </span>
        </div>
        <p class="text-2xl font-extrabold tracking-tight">{{ $stats['total_reviews'] ?? 0 }}</p>
        @if(($stats['pending_reviews'] ?? 0) > 0)
            <p class="text-xs text-brand-600 mt-1">{{ $stats['pending_reviews'] }} pending</p>
        @endif
    </div>
    <div class="border border-neutral-200 bg-white p-5 hover:border-black transition-colors duration-200 group">
        <div class="flex items-start justify-between mb-3">
            <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500">Authors</p>
            <span class="w-8 h-8 rounded-lg bg-black flex items-center justify-center text-white group-hover:bg-brand-600 transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </span>
        </div>
        <p class="text-2xl font-extrabold tracking-tight">{{ $stats['total_authors'] ?? 0 }}</p>
    </div>
    <div class="border border-neutral-200 bg-white p-5 hover:border-black transition-colors duration-200 group">
        <div class="flex items-start justify-between mb-3">
            <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500">Users</p>
            <span class="w-8 h-8 rounded-lg bg-black flex items-center justify-center text-white group-hover:bg-brand-600 transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
            </span>
        </div>
        <p class="text-2xl font-extrabold tracking-tight">{{ $stats['total_users'] ?? 0 }}</p>
    </div>
    <div class="border border-neutral-200 bg-white p-5 hover:border-black transition-colors duration-200 group">
        <div class="flex items-start justify-between mb-3">
            <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500">Blog Posts</p>
            <span class="w-8 h-8 rounded-lg bg-black flex items-center justify-center text-white group-hover:bg-brand-600 transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            </span>
        </div>
        <p class="text-2xl font-extrabold tracking-tight">{{ $stats['total_blogs'] ?? 0 }}</p>
    </div>
    <div class="border border-neutral-200 bg-white p-5 hover:border-black transition-colors duration-200 group">
        <div class="flex items-start justify-between mb-3">
            <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500">Categories</p>
            <span class="w-8 h-8 rounded-lg bg-black flex items-center justify-center text-white group-hover:bg-brand-600 transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </span>
        </div>
        <p class="text-2xl font-extrabold tracking-tight">{{ $stats['total_categories'] ?? 0 }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Recent Orders --}}
    <div class="lg:col-span-2 border border-neutral-200 bg-white">
        <div class="flex items-center justify-between px-6 py-4 border-b border-neutral-200">
            <h3 class="text-sm font-bold tracking-tight">Recent Orders</h3>
            <a href="{{ route('admin.orders') }}" class="text-xs font-semibold uppercase tracking-wider text-neutral-500 hover:text-black transition-colors">View All</a>
        </div>
        @if($recentOrders->count())
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-neutral-50 text-left">
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider">Items</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @foreach($recentOrders as $order)
                        <tr class="hover:bg-neutral-50 transition-colors">
                            <td class="px-6 py-3">
                                <a href="{{ route('admin.orders.show', $order) }}" class="font-medium hover:text-brand-600 transition-colors">#{{ $order->invoice_no ?? $order->id }}</a>
                            </td>
                            <td class="px-6 py-3 text-neutral-600">{{ $order->items_count }} items</td>
                            <td class="px-6 py-3 font-medium">৳{{ number_format($order->total) }}</td>
                            <td class="px-6 py-3">
                                <span class="inline-block text-xs font-semibold uppercase tracking-wider
                                    {{ $order->payment_status === 'completed' ? 'text-brand-600' : 'text-neutral-400' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-neutral-500 text-xs">{{ $order->created_at->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="p-8 text-center text-sm text-neutral-500">No orders yet.</div>
        @endif
    </div>

    {{-- Quick Actions --}}
    <div class="border border-neutral-200 bg-white p-6">
        <h3 class="text-sm font-bold tracking-tight mb-5">Quick Actions</h3>
        <div class="space-y-2">
            <a href="{{ route('admin.books') }}" class="flex items-center gap-3 px-4 py-3 border border-neutral-200 hover:border-black transition-colors text-sm font-medium group">
                <svg class="w-4 h-4 text-neutral-400 group-hover:text-black transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Add New Book
            </a>
            <a href="{{ route('admin.blogs.create') }}" class="flex items-center gap-3 px-4 py-3 border border-neutral-200 hover:border-black transition-colors text-sm font-medium group">
                <svg class="w-4 h-4 text-neutral-400 group-hover:text-black transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Write New Post
            </a>
            <a href="{{ route('admin.orders') }}" class="flex items-center gap-3 px-4 py-3 border border-neutral-200 hover:border-black transition-colors text-sm font-medium group">
                <svg class="w-4 h-4 text-neutral-400 group-hover:text-black transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                View Orders
            </a>
            <a href="{{ route('admin.settings') }}" class="flex items-center gap-3 px-4 py-3 border border-neutral-200 hover:border-black transition-colors text-sm font-medium group">
                <svg class="w-4 h-4 text-neutral-400 group-hover:text-black transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Site Settings
            </a>
        </div>
    </div>
</div>
@endsection
