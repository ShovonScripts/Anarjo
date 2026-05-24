@php $header = 'Dashboard' @endphp
@extends('layouts.admin')

@section('title', 'Dashboard - Admin - ' . config('app.name'))

@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
    <div class="border border-neutral-200 bg-white p-6">
        <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-1">Total Books</p>
        <p class="text-3xl font-extrabold tracking-tight">{{ $stats['total_books'] ?? 0 }}</p>
    </div>
    <div class="border border-neutral-200 bg-white p-6">
        <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-1">Total Orders</p>
        <p class="text-3xl font-extrabold tracking-tight">{{ $stats['total_orders'] ?? 0 }}</p>
    </div>
    <div class="border border-neutral-200 bg-white p-6">
        <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-1">Total Authors</p>
        <p class="text-3xl font-extrabold tracking-tight">{{ $stats['total_authors'] ?? 0 }}</p>
    </div>
    <div class="border border-neutral-200 bg-white p-6">
        <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-1">Total Users</p>
        <p class="text-3xl font-extrabold tracking-tight">{{ $stats['total_users'] ?? 0 }}</p>
    </div>
</div>

<div class="border border-neutral-200 bg-white p-8 text-center">
    <p class="text-neutral-500">Welcome to the Anarjo admin panel. Use the sidebar to manage your content.</p>
</div>
@endsection
