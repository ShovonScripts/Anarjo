@extends('layouts.frontend')

@section('title', $book->title . ' - ' . config('app.name'))
@section('meta_description', $book->seo_description ?? $book->excerpt)

@php $seo = App\Services\Seo\SeoService::meta($book->title, $book->seo_description ?? $book->excerpt ?? '', $book->cover_image ?? '', route('books.show', $book->slug), 'book'); @endphp
@section('seo')
    @include('seo.meta', ['seo' => $seo])
    @include('seo.schema-book', ['book' => $book])
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
        {{-- Book Cover --}}
        <div>
            <div class="bg-neutral-100 overflow-hidden aspect-[3/4] flex items-center justify-center border border-neutral-200">
                @if($book->cover_image)
                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                @else
                    <span class="text-6xl text-neutral-300">+</span>
                @endif
            </div>
        </div>

        {{-- Book Details --}}
        <div class="flex flex-col justify-center">
            @if($book->category)
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-3">{{ $book->category->name }}</p>
            @endif
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight leading-tight">{{ $book->title }}</h1>

            @if($book->author)
                <p class="text-lg text-neutral-600 mt-4">
                    By <a href="{{ route('author.show', $book->author->slug) }}" class="font-semibold text-black hover:text-brand-600 underline underline-offset-4 transition-colors">{{ $book->author->name }}</a>
                </p>
            @endif

            <div class="flex items-center gap-4 mt-8">
                @if($book->sale_price)
                    <span class="text-4xl font-extrabold text-brand-600">৳{{ number_format($book->sale_price) }}</span>
                    <span class="text-xl text-neutral-400 line-through">৳{{ number_format($book->price) }}</span>
                @else
                    <span class="text-4xl font-extrabold">৳{{ number_format($book->price) }}</span>
                @endif
            </div>

            <div class="flex gap-4 mt-8">
                <livewire:add-to-cart-button :book-id="$book->id" :book-title="$book->title" size="lg" :key="'cart-' . $book->id" />
                <button class="btn-outline px-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </button>
            </div>

            @if($book->description)
                <div class="mt-10 border-t border-neutral-200 pt-8">
                    <h3 class="text-sm font-semibold uppercase tracking-widest mb-4">About This Book</h3>
                    <p class="text-neutral-600 leading-relaxed">{{ $book->description }}</p>
                </div>
            @endif

            {{-- Book Info --}}
            <div class="mt-8 border border-neutral-200 divide-y divide-neutral-200">
                @if($book->isbn) <div class="flex justify-between px-5 py-3 text-sm"><span class="font-medium">ISBN</span><span class="text-neutral-600">{{ $book->isbn }}</span></div> @endif
                @if($book->pages) <div class="flex justify-between px-5 py-3 text-sm"><span class="font-medium">Pages</span><span class="text-neutral-600">{{ $book->pages }}</span></div> @endif
                @if($book->language) <div class="flex justify-between px-5 py-3 text-sm"><span class="font-medium">Language</span><span class="text-neutral-600">{{ $book->language }}</span></div> @endif
                @if($book->edition) <div class="flex justify-between px-5 py-3 text-sm"><span class="font-medium">Edition</span><span class="text-neutral-600">{{ $book->edition }}</span></div> @endif
                @if($book->publish_date) <div class="flex justify-between px-5 py-3 text-sm"><span class="font-medium">Published</span><span class="text-neutral-600">{{ $book->publish_date->format('M Y') }}</span></div> @endif
                @if($book->weight) <div class="flex justify-between px-5 py-3 text-sm"><span class="font-medium">Weight</span><span class="text-neutral-600">{{ $book->weight }} kg</span></div> @endif
            </div>
        </div>
    </div>

    {{-- Related Books --}}
    @if($relatedBooks->count())
    <div class="mt-20 border-t border-neutral-200 pt-16">
        <h2 class="section-heading mb-10">Related Books</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            @foreach($relatedBooks as $related)
                <x-book-card :book="$related" />
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
