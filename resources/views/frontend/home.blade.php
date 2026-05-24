@extends('layouts.frontend')

@section('title', config('app.name'))

@section('content')
    {{-- Hero Section --}}
    <section class="border-b border-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32">
            <div class="max-w-3xl">
                <span class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600">Est. 2020</span>
                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-black mt-6 leading-tight">
                    Words That<br>
                    <span class="text-brand-600">Move Nations</span>
                </h1>
                <p class="text-lg text-neutral-600 mt-6 max-w-xl leading-relaxed">
                    Discover, Read, and Grow with Bangladesh's Finest Collection of books spanning fiction, academic, Islamic, and children's literature.
                </p>
                <div class="flex gap-4 mt-10">
                    <a href="{{ route('books.index') }}" class="btn-primary">Explore Books</a>
                    <a href="{{ route('about') }}" class="btn-outline">About Us</a>
                </div>
            </div>
        </div>
    </section>

    {{-- Categories Grid --}}
    <section class="border-b border-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="flex items-baseline justify-between mb-12">
                <h2 class="section-heading">Browse by<br>Category</h2>
                <a href="{{ route('books.index') }}" class="text-sm font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">View All →</a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-0 border-t border-l border-neutral-200">
                @foreach(['গল্প', 'উপন্যাস', 'ইসলামিক', 'শিশু', 'একাডেমিক', 'রিসার্চ'] as $index => $cat)
                    <a href="#" class="group border-r border-b border-neutral-200 p-8 text-center hover:bg-black transition-colors duration-300">
                        <p class="text-sm font-semibold uppercase tracking-wider group-hover:text-white transition-colors">{{ $cat }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Featured Books --}}
    @if($featuredBooks->count())
    <section class="border-b border-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="flex items-baseline justify-between mb-12">
                <h2 class="section-heading">Featured<br>Books</h2>
                <span class="text-xs uppercase tracking-[0.15em] text-neutral-400">Curated Picks</span>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($featuredBooks as $book)
                    <x-book-card :book="$book" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Bestsellers --}}
    @if($bestsellers->count())
    <section class="border-b border-neutral-200 bg-neutral-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="flex items-baseline justify-between mb-12">
                <h2 class="section-heading">Best&shy;sellers</h2>
                <span class="text-xs uppercase tracking-[0.15em] text-neutral-400">Most Popular</span>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($bestsellers as $book)
                    <x-book-card :book="$book" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Author Strip --}}
    @if($authors->count())
    <section class="border-b border-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <h2 class="section-heading mb-12">Our Authors</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-8">
                @foreach($authors as $author)
                    <a href="{{ route('author.show', $author->slug) }}" class="group text-center">
                        <div class="w-20 h-20 mx-auto mb-3 overflow-hidden bg-neutral-200 border-2 border-neutral-200 group-hover:border-black transition-colors">
                            @if($author->photo)
                                <img src="{{ asset('storage/' . $author->photo) }}" alt="{{ $author->name }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <p class="text-sm font-semibold group-hover:text-brand-600 transition-colors">{{ $author->name }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Blog Section --}}
    @if($latestBlogs->count())
    <section class="bg-neutral-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="flex items-baseline justify-between mb-12">
                <h2 class="section-heading">Latest from<br>the Blog</h2>
                <a href="{{ route('blog.index') }}" class="text-sm font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">All Posts →</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-0 border-t border-l border-neutral-200">
                @foreach($latestBlogs as $blog)
                    <a href="{{ route('blog.show', $blog->slug) }}" class="group border-r border-b border-neutral-200 p-8 hover:bg-black transition-colors duration-300">
                        <p class="text-xs uppercase tracking-widest text-neutral-400 mb-3">{{ $blog->published_at?->format('M d, Y') }}</p>
                        <h3 class="font-bold text-lg mb-3 group-hover:text-white transition-colors">{{ $blog->title }}</h3>
                        <p class="text-sm text-neutral-600 leading-relaxed group-hover:text-neutral-300 transition-colors">{{ Str::limit(strip_tags($blog->content), 120) }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
@endsection
