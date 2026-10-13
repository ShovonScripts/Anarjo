@extends('layouts.frontend')

@section('title', $book->title . ' - ' . config('app.name'))
@section('meta_description', $book->seo_description ?? $book->excerpt)

@php $seo = App\Services\Seo\SeoService::meta($book->title, $book->seo_description ?? $book->excerpt ?? '', $book->cover_image ?? '', route('books.show', $book->slug), 'book'); @endphp
@section('seo')
    @include('seo.meta', ['seo' => $seo])
    @include('seo.schema-book', ['book' => $book])
@endsection

@section('content')
<x-breadcrumbs :items="array_filter([['label' => 'Books', 'url' => route('books.index')], $book->categories->first() ? ['label' => $book->categories->first()->name, 'url' => route('category.books', $book->categories->first()->slug)] : null, ['label' => $book->title]])" />

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
    {{-- Main Product Showcase Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-14 items-start">

        {{-- Left: Book Cover Presentation (5 Cols) --}}
        <div class="lg:col-span-5 lg:sticky lg:top-28" x-data="{ lightbox: false }">
            <div class="bg-gradient-to-b from-neutral-100 to-neutral-200/60 border border-neutral-200/80 p-6 sm:p-10 flex flex-col items-center justify-center relative overflow-hidden group">

                {{-- Badges on Cover --}}
                <div class="absolute top-4 left-4 z-10 flex flex-col gap-1.5 items-start">
                    @if($book->pre_order)
                        <span class="bg-amber-500 text-amber-950 text-xs font-bold uppercase tracking-wider px-3 py-1 shadow-sm">Pre-Order</span>
                    @elseif($book->sale_price)
                        @php $discountPercent = round((($book->price - $book->sale_price) / $book->price) * 100); @endphp
                        <span class="bg-brand-600 text-white text-xs font-extrabold uppercase tracking-wider px-3 py-1 shadow-sm">{{ $discountPercent }}% OFF</span>
                    @endif
                    @if($book->bestseller)
                        <span class="bg-neutral-900 text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-0.5">Bestseller</span>
                    @endif
                </div>

                {{-- 3D Realistic Book Display --}}
                <div @click="lightbox = true" class="relative cursor-zoom-in w-full max-w-[280px] sm:max-w-[320px] aspect-[3/4] transition-transform duration-500 group-hover:scale-[1.02]">
                    <div class="w-full h-full bg-white shadow-[0_20px_40px_rgba(0,0,0,0.18),0_5px_15px_rgba(0,0,0,0.08)] border-r-2 border-neutral-300 overflow-hidden relative">
                        {{-- Book spine shadow overlay --}}
                        <div class="absolute inset-y-0 left-0 w-3 bg-gradient-to-r from-black/20 via-black/5 to-transparent z-10 pointer-events-none"></div>

                        @if($book->cover_image)
                            <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover" loading="eager" decoding="async">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-neutral-700 to-neutral-800 text-white p-6 text-center">
                                <x-icon name="book" class="w-12 h-12 text-white/40 mb-3" />
                                <span class="font-serif text-lg font-bold">{{ $book->title }}</span>
                            </div>
                        @endif
                    </div>

                    {{-- Hover Zoom Hint --}}
                    <div class="absolute bottom-3 right-3 bg-black/70 backdrop-blur-sm text-white text-[11px] font-medium px-2.5 py-1 flex items-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                        <span>Click to Zoom</span>
                    </div>
                </div>

                {{-- Sample PDF Preview Button (if available) --}}
                @if($book->preview_pdf)
                    <div class="mt-6 w-full max-w-[320px]">
                        <a href="{{ asset('storage/' . $book->preview_pdf) }}" target="_blank" class="w-full inline-flex items-center justify-center gap-2 border border-neutral-900 bg-white hover:bg-neutral-900 hover:text-white text-neutral-900 py-2.5 px-4 text-xs font-semibold uppercase tracking-wider transition-all duration-200 shadow-sm">
                            <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            <span>Read Sample PDF</span>
                        </a>
                    </div>
                @endif
            </div>

            {{-- Fullscreen Image Lightbox --}}
            <div x-show="lightbox" x-cloak x-transition.opacity.duration.300ms class="fixed inset-0 z-50 bg-black/95 flex items-center justify-center p-4" @click="lightbox = false" @keydown.escape.window="lightbox = false">
                <button @click="lightbox = false" class="absolute top-6 right-6 text-white/80 hover:text-white p-2 transition-colors" aria-label="Close image">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                @if($book->cover_image)
                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="max-w-full max-h-[90vh] object-contain shadow-2xl" @click.stop>
                @endif
            </div>
        </div>

        {{-- Right: Book Information & Purchase Controls (7 Cols) --}}
        <div class="lg:col-span-7 space-y-6">

            {{-- Categories Badges --}}
            @if($book->categories->count())
                <div class="flex flex-wrap items-center gap-2">
                    @foreach($book->categories as $cat)
                        <a href="{{ route('category.books', $cat->slug) }}" class="inline-block bg-neutral-100 hover:bg-neutral-900 hover:text-white text-neutral-700 text-[11px] font-bold uppercase tracking-wider px-3 py-1 transition-colors">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Book Title & Author --}}
            <div>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-neutral-900 tracking-tight leading-tight">
                    {{ $book->title }}
                </h1>

                @if($book->author)
                    <div class="flex items-center gap-2.5 mt-3 text-sm">
                        <span class="text-neutral-500 font-medium">By</span>
                        <a href="{{ route('books.author', $book->author->slug) }}" class="font-bold text-neutral-900 hover:text-brand-600 underline underline-offset-4 transition-colors">
                            {{ $book->author->name }}
                        </a>
                    </div>
                @endif
            </div>

            {{-- Pricing & Stock Box --}}
            <div class="bg-neutral-50 border border-neutral-200 p-5 sm:p-6 space-y-3">
                <div class="flex flex-wrap items-baseline justify-between gap-4">
                    <div class="flex items-baseline gap-3">
                        @if($book->sale_price)
                            <span class="text-3xl sm:text-4xl font-extrabold text-brand-600 font-mono">৳{{ number_format($book->sale_price) }}</span>
                            <span class="text-lg text-neutral-400 line-through font-mono">৳{{ number_format($book->price) }}</span>
                            <span class="text-xs font-bold text-green-700 bg-green-100 px-2 py-0.5 rounded">Save ৳{{ number_format($book->price - $book->sale_price) }}</span>
                        @else
                            <span class="text-3xl sm:text-4xl font-extrabold text-neutral-900 font-mono">৳{{ number_format($book->price) }}</span>
                        @endif
                    </div>

                    {{-- Stock Badge --}}
                    <div>
                        @if($book->pre_order)
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider bg-amber-100 text-amber-900 border border-amber-300 px-3 py-1">
                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                                Pre-Order
                            </span>
                        @elseif($book->stock < 1)
                            <span class="inline-flex items-center text-xs font-bold uppercase tracking-wider bg-neutral-200 text-neutral-600 px-3 py-1">
                                Out of Stock
                            </span>
                        @elseif($book->stock <= 5)
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider bg-orange-100 text-orange-800 border border-orange-300 px-3 py-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-orange-600 animate-pulse"></span>
                                Only {{ $book->stock }} copies left
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider bg-green-100 text-green-800 border border-green-300 px-3 py-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span>
                                In Stock &bull; Ready to Ship
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Action Row: Add to Cart, Like, Wishlist --}}
                <div class="pt-3 flex flex-wrap items-center gap-3">
                    <div class="flex-1 min-w-[200px]">
                        <livewire:add-to-cart-button :book-id="$book->id" :book-title="$book->title" size="lg" :key="'cart-' . $book->id" />
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="border border-neutral-300 bg-white hover:bg-neutral-50 px-3.5 py-3 transition-colors">
                            <livewire:book-like :book-id="$book->id" :key="'like-' . $book->id" />
                        </div>
                        <div class="border border-neutral-300 bg-white hover:bg-neutral-50 px-3.5 py-3 transition-colors">
                            <livewire:book-wishlist :book-id="$book->id" :key="'wishlist-' . $book->id" />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Trust & Service Guarantees (4-Pill Grid) --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs text-neutral-600">
                <div class="bg-white border border-neutral-200 p-3 flex flex-col items-center text-center gap-1.5 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                    <svg class="w-5 h-5 text-neutral-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                    <span class="font-bold text-neutral-900">Fast Delivery</span>
                    <span class="text-[10px] text-neutral-400">Dhaka 24-48h, Nationwide 2-4d</span>
                </div>
                <div class="bg-white border border-neutral-200 p-3 flex flex-col items-center text-center gap-1.5 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                    <svg class="w-5 h-5 text-neutral-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span class="font-bold text-neutral-900">Cash on Delivery</span>
                    <span class="text-[10px] text-neutral-400">bKash &amp; Cards Supported</span>
                </div>
                <div class="bg-white border border-neutral-200 p-3 flex flex-col items-center text-center gap-1.5 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                    <svg class="w-5 h-5 text-neutral-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span class="font-bold text-neutral-900">100% Original</span>
                    <span class="text-[10px] text-neutral-400">Direct From Publication</span>
                </div>
                <div class="bg-white border border-neutral-200 p-3 flex flex-col items-center text-center gap-1.5 shadow-[0_1px_2px_rgba(0,0,0,0.02)]">
                    <svg class="w-5 h-5 text-neutral-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span class="font-bold text-neutral-900">Easy Return</span>
                    <span class="text-[10px] text-neutral-400">7-Day Replacement Policy</span>
                </div>
            </div>

            {{-- Synopsis & Description --}}
            @if($book->description)
                <div class="bg-white border border-neutral-200 p-6 sm:p-7">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-neutral-900 border-b border-neutral-100 pb-3 mb-4">
                        About This Book
                    </h3>
                    <div class="prose prose-sm max-w-none text-neutral-700 leading-relaxed font-sans text-sm sm:text-base">
                        {!! nl2br(e($book->description)) !!}
                    </div>
                </div>
            @endif

            {{-- Book Specifications Matrix --}}
            <div class="bg-white border border-neutral-200 p-6 sm:p-7">
                <h3 class="text-sm font-bold uppercase tracking-wider text-neutral-900 border-b border-neutral-100 pb-3 mb-4">
                    Book Specifications
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-3 text-sm">
                    @if($book->isbn)
                        <div class="flex justify-between py-2 border-b border-neutral-100">
                            <span class="text-neutral-500 font-medium">ISBN</span>
                            <span class="font-semibold text-neutral-900 font-mono">{{ $book->isbn }}</span>
                        </div>
                    @endif
                    @if($book->pages)
                        <div class="flex justify-between py-2 border-b border-neutral-100">
                            <span class="text-neutral-500 font-medium">Total Pages</span>
                            <span class="font-semibold text-neutral-900">{{ $book->pages }} Pages</span>
                        </div>
                    @endif
                    @if($book->language)
                        <div class="flex justify-between py-2 border-b border-neutral-100">
                            <span class="text-neutral-500 font-medium">Language</span>
                            <span class="font-semibold text-neutral-900">{{ $book->language }}</span>
                        </div>
                    @endif
                    @if($book->edition)
                        <div class="flex justify-between py-2 border-b border-neutral-100">
                            <span class="text-neutral-500 font-medium">Edition</span>
                            <span class="font-semibold text-neutral-900">{{ $book->edition }}</span>
                        </div>
                    @endif
                    @if($book->publish_date)
                        <div class="flex justify-between py-2 border-b border-neutral-100">
                            <span class="text-neutral-500 font-medium">Published Date</span>
                            <span class="font-semibold text-neutral-900">{{ $book->publish_date->format('F Y') }}</span>
                        </div>
                    @endif
                    @if($book->weight)
                        <div class="flex justify-between py-2 border-b border-neutral-100">
                            <span class="text-neutral-500 font-medium">Weight</span>
                            <span class="font-semibold text-neutral-900">{{ $book->weight }} kg</span>
                        </div>
                    @endif
                    @if($book->bookFairEditions->count())
                        <div class="flex justify-between py-2 border-b border-neutral-100 sm:col-span-2">
                            <span class="text-neutral-500 font-medium">Book Fair Edition</span>
                            <span class="font-semibold text-neutral-900">
                                @foreach($book->bookFairEditions as $fe)
                                    <a href="{{ route('book-fair.show', $fe->slug) }}" class="hover:text-brand-600 underline transition-colors">{{ $fe->title }}</a>@if(!$loop->last), @endif
                                @endforeach
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Author Bio Spotlight (if author exists) --}}
            @if($book->author)
                <div class="bg-neutral-900 text-white p-6 sm:p-7 flex flex-col sm:flex-row items-center sm:items-start gap-5">
                    @if($book->author->photo)
                        <img src="{{ asset('storage/' . $book->author->photo) }}" alt="{{ $book->author->name }}" class="w-16 h-16 rounded-full object-cover border-2 border-white/20 flex-shrink-0">
                    @else
                        <div class="w-16 h-16 rounded-full bg-neutral-800 border-2 border-white/20 flex items-center justify-center font-bold text-lg text-neutral-300 flex-shrink-0">
                            {{ substr($book->author->name, 0, 1) }}
                        </div>
                    @endif
                    <div class="flex-1 text-center sm:text-left">
                        <p class="text-xs uppercase tracking-widest text-brand-400 font-bold mb-1">About the Author</p>
                        <h4 class="text-lg font-bold">{{ $book->author->name }}</h4>
                        @if($book->author->bio)
                            <p class="text-xs text-neutral-400 mt-1.5 line-clamp-2 leading-relaxed">{{ $book->author->bio }}</p>
                        @endif
                        <a href="{{ route('books.author', $book->author->slug) }}" class="inline-flex items-center gap-1.5 mt-3 text-xs font-semibold text-white hover:text-brand-400 underline underline-offset-4 transition-colors">
                            Explore all books by {{ $book->author->name }} &rarr;
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- Reviews Section --}}
    <div class="mt-16 sm:mt-20 border-t border-neutral-200 pt-12">
        <livewire:book-reviews :book-id="$book->id" :key="'reviews-' . $book->id" />
    </div>

    {{-- Related Books Section --}}
    @if($relatedBooks->count())
        <div class="mt-16 sm:mt-20 border-t border-neutral-200 pt-12">
            <div class="flex items-center justify-between mb-8">
                <h2 class="section-heading text-2xl sm:text-3xl">Related Books</h2>
                <a href="{{ route('books.index') }}" class="text-xs font-bold uppercase tracking-wider text-neutral-600 hover:text-neutral-900 transition-colors">
                    View All &rarr;
                </a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 sm:gap-6">
                @foreach($relatedBooks as $related)
                    <x-book-card :book="$related" />
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
