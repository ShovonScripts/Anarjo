@php $header = 'Dashboard' @endphp
@extends('layouts.admin')

@section('title', 'Dashboard - Admin - ' . config('app.name'))

@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
    <div class="border border-neutral-200 bg-white p-6">
        <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-1">Revenue</p>
        <p class="text-3xl font-extrabold tracking-tight">৳{{ number_format($stats['revenue'] ?? 0) }}</p>
    </div>
    <div class="border border-neutral-200 bg-white p-6">
        <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-1">Orders</p>
        <p class="text-3xl font-extrabold tracking-tight">{{ $stats['total_orders'] ?? 0 }}</p>
        <p class="text-xs text-brand-600 mt-1">{{ $stats['pending_orders'] ?? 0 }} pending</p>
    </div>
    <div class="border border-neutral-200 bg-white p-6">
        <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-1">Books</p>
        <p class="text-3xl font-extrabold tracking-tight">{{ $stats['total_books'] ?? 0 }}</p>
    </div>
    <div class="border border-neutral-200 bg-white p-6">
        <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-1">Reviews</p>
        <p class="text-3xl font-extrabold tracking-tight">{{ $stats['total_reviews'] ?? 0 }}</p>
        <p class="text-xs text-brand-600 mt-1">{{ $stats['pending_reviews'] ?? 0 }} pending</p>
    </div>
    <div class="border border-neutral-200 bg-white p-6">
        <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-1">Authors</p>
        <p class="text-3xl font-extrabold tracking-tight">{{ $stats['total_authors'] ?? 0 }}</p>
    </div>
    <div class="border border-neutral-200 bg-white p-6">
        <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-1">Users</p>
        <p class="text-3xl font-extrabold tracking-tight">{{ $stats['total_users'] ?? 0 }}</p>
    </div>
    <div class="border border-neutral-200 bg-white p-6">
        <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-1">Blog Posts</p>
        <p class="text-3xl font-extrabold tracking-tight">{{ $stats['total_blogs'] ?? 0 }}</p>
    </div>
    <div class="border border-neutral-200 bg-white p-6">
        <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-1">Categories</p>
        <p class="text-3xl font-extrabold tracking-tight">{{ $stats['total_categories'] ?? 0 }}</p>
    </div>
</div>

<div class="border border-neutral-200 bg-white p-8 text-center">
    <p class="text-neutral-500">Welcome to the Anarjo admin panel. Use the sidebar to manage your content.</p>
</div>
@endsection
