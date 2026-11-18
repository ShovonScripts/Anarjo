@extends('layouts.frontend')

@section('title', $item->title . ' - ' . config('app.name'))

@section('content')
<x-breadcrumbs :items="[['label' => 'Stationeries', 'url' => route('stationeries.index')], $item->brand ? ['label' => $item->brand->name, 'url' => route('stationeries.brand', $item->brand->slug)] : null, ['label' => $item->title]]" />
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
        {{-- Image --}}
        <div class="bg-neutral-100 overflow-hidden aspect-[1/1] flex items-center justify-center border border-neutral-200">
            @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
            @else
                <span class="text-6xl text-neutral-300">+</span>
            @endif
        </div>

        {{-- Details --}}
        <div class="flex flex-col justify-center">
            @if($item->brand)
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-3">{{ $item->brand->name }}</p>
            @endif
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight leading-tight">{{ $item->title }}</h1>

            <div class="flex items-center gap-4 mt-8">
                @if($item->sale_price)
                    <span class="text-4xl font-extrabold text-brand-600">৳{{ number_format($item->sale_price) }}</span>
                    <span class="text-xl text-neutral-400 line-through">৳{{ number_format($item->price) }}</span>
                @else
                    <span class="text-4xl font-extrabold">৳{{ number_format($item->price) }}</span>
                @endif
                @if($item->stock < 1)
                    <span class="text-xs font-bold uppercase tracking-wider bg-neutral-100 text-neutral-600 border border-neutral-300 px-3 py-1.5">Out of Stock</span>
                @elseif($item->stock <= 5)
                    <span class="text-xs font-bold uppercase tracking-wider bg-orange-100 text-orange-700 border border-orange-300 px-3 py-1.5">Only {{ $item->stock }} left</span>
                @else
                    <span class="text-xs font-bold uppercase tracking-wider bg-green-100 text-green-700 border border-green-300 px-3 py-1.5">In Stock</span>
                @endif
            </div>

            <div class="flex gap-4 mt-8">
                <livewire:add-to-cart-button :book-id="$item->id" :book-title="$item->title" size="lg" type="stationery" :key="'cart-' . $item->id" />
            </div>

            @if($item->description)
                <div class="mt-10 border-t border-neutral-200 pt-8">
                    <h3 class="text-sm font-semibold uppercase tracking-widest mb-4">Description</h3>
                    <p class="text-neutral-600 leading-relaxed">{{ $item->description }}</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Related --}}
    @if($related->count())
    <div class="mt-20 border-t border-neutral-200 pt-16">
        <h2 class="section-heading mb-10">More from {{ $item->brand?->name ?? 'this brand' }}</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($related as $rel)
                <x-stationery-card :item="$rel" />
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
