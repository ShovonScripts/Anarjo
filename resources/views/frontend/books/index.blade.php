@extends('layouts.frontend')

@section('title', (request('search') ? 'Search: ' . request('search') . ' - ' . config('app.name') : 'Books - ' . config('app.name')))

@section('content')
<x-breadcrumbs :items="[['label' => 'Books']]" />
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 lg:gap-12">
        {{-- Filters Sidebar --}}
        <aside class="lg:col-span-1">
            <div class="lg:space-y-6 lg:sticky lg:top-24">
                {{-- Mobile filter toggle --}}
                <div x-data="{ showFilters: false, catSearch: '', authSearch: '' }" class="lg:hidden mb-4">
                    <button @click="showFilters = !showFilters" class="w-full flex items-center justify-between border border-neutral-200 px-4 py-3 text-sm font-semibold uppercase tracking-wider bg-white">
                        <span>Filters</span>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': showFilters }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="showFilters" x-collapse class="mt-4 space-y-4">
                        <div class="border border-neutral-200 p-4">
                            <h3 class="text-xs font-semibold uppercase tracking-widest mb-4">Categories</h3>
                            <input x-model="catSearch" type="text" placeholder="Search categories..." class="w-full border border-neutral-200 bg-neutral-50 px-3 py-2 text-xs mb-3 placeholder-neutral-400 focus:border-neutral-900 focus:ring-0 focus:outline-none transition-colors">
                            <ul class="space-y-0.5 max-h-60 overflow-y-auto">
                                <li>
                                    <a href="{{ route('books.index') }}"
                                       class="block px-3 py-2 text-sm {{ !request()->route('slug') && !request('search') ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:bg-neutral-100' }} transition-colors">
                                        All Books
                                    </a>
                                </li>
                                @foreach($categories as $cat)
                                    <li x-show="!catSearch || '{{ strtolower($cat->name) }}'.includes(catSearch.toLowerCase())">
                                        <a href="{{ route('category.books', $cat->slug) }}"
                                           class="block px-3 py-2 text-sm {{ isset($category) && $category->id === $cat->id ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:bg-neutral-100' }} transition-colors">
                                            {{ $cat->name }}
                                            <span class="text-xs opacity-60">({{ $cat->books_count }})</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @if(isset($authors) && $authors->count())
                        <div class="border border-neutral-200 p-4">
                            <h3 class="text-xs font-semibold uppercase tracking-widest mb-4">Writers</h3>
                            <input x-model="authSearch" type="text" placeholder="Search writers..." class="w-full border border-neutral-200 bg-neutral-50 px-3 py-2 text-xs mb-3 placeholder-neutral-400 focus:border-neutral-900 focus:ring-0 focus:outline-none transition-colors">
                            <ul class="space-y-0.5 max-h-60 overflow-y-auto">
                                @foreach($authors as $auth)
                                    <li x-show="!authSearch || '{{ strtolower($auth->name) }}'.includes(authSearch.toLowerCase())">
                                        <a href="{{ route('books.author', $auth->slug) }}"
                                           class="block px-3 py-2 text-sm {{ isset($author) && $author->id === $auth->id ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:bg-neutral-100' }} transition-colors">
                                            {{ $auth->name }}
                                            <span class="text-xs opacity-60">({{ $auth->books_count }})</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Desktop sidebar --}}
                <div x-data="{ catSearch: '', authSearch: '' }" class="hidden lg:block space-y-6">
                    <div class="border border-neutral-200 p-6">
                        <h3 class="text-sm font-semibold uppercase tracking-widest mb-6">Categories</h3>
                        <input x-model="catSearch" type="text" placeholder="Search categories..." class="w-full border border-neutral-200 bg-neutral-50 px-3 py-2 text-xs mb-4 placeholder-neutral-400 focus:border-neutral-900 focus:ring-0 focus:outline-none transition-colors">
                        <ul class="space-y-1 max-h-72 overflow-y-auto">
                            <li>
                                <a href="{{ route('books.index') }}"
                                   class="block px-3 py-2 text-sm {{ !request()->route('slug') && !request('search') ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:bg-neutral-100' }} transition-colors">
                                    All Books
                                </a>
                            </li>
                            @foreach($categories as $cat)
                                <li x-show="!catSearch || '{{ strtolower($cat->name) }}'.includes(catSearch.toLowerCase())">
                                    <a href="{{ route('category.books', $cat->slug) }}"
                                       class="block px-3 py-2 text-sm {{ isset($category) && $category->id === $cat->id ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:bg-neutral-100' }} transition-colors">
                                        {{ $cat->name }}
                                        <span class="text-xs opacity-60">({{ $cat->books_count }})</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    @if(isset($authors) && $authors->count())
                    <div class="border border-neutral-200 p-6">
                        <h3 class="text-sm font-semibold uppercase tracking-widest mb-6">Writers</h3>
                        <input x-model="authSearch" type="text" placeholder="Search writers..." class="w-full border border-neutral-200 bg-neutral-50 px-3 py-2 text-xs mb-4 placeholder-neutral-400 focus:border-neutral-900 focus:ring-0 focus:outline-none transition-colors">
                        <ul class="space-y-1 max-h-72 overflow-y-auto">
                            @foreach($authors as $auth)
                                <li x-show="!authSearch || '{{ strtolower($auth->name) }}'.includes(authSearch.toLowerCase())">
                                    <a href="{{ route('books.author', $auth->slug) }}"
                                       class="block px-3 py-2 text-sm {{ isset($author) && $author->id === $auth->id ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:bg-neutral-100' }} transition-colors">
                                        {{ $auth->name }}
                                        <span class="text-xs opacity-60">({{ $auth->books_count }})</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </aside>

        {{-- Books Grid --}}
        <div class="lg:col-span-3">
            @php
                $hasActiveFilters = isset($category) || isset($author) || request('search');
            @endphp

            @if(request('search'))
                @php $searchTerm = request('search'); @endphp
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-2">Search Results</p>
                <h1 class="section-heading mb-2">"{{ $searchTerm }}"</h1>
                <p class="text-sm text-neutral-500 mb-6 md:mb-8">{{ $books->total() }} {{ Str::plural('result', $books->total()) }} found</p>
            @elseif(isset($category))
                <h1 class="section-heading mb-6 md:mb-8">{{ $category->name }}</h1>
            @elseif(isset($author))
                <h1 class="section-heading mb-6 md:mb-8">{{ $author->name }}</h1>
            @else
                <h1 class="section-heading mb-6 md:mb-8">All Books</h1>
            @endif

            {{-- Active Filter Chips --}}
            @if($hasActiveFilters)
                <div class="flex flex-wrap items-center gap-2 mb-6 pb-6 border-b border-neutral-200">
                    @if(isset($category))
                        <a href="{{ route('books.index') }}" class="inline-flex items-center gap-1.5 bg-brand-50 text-brand-700 border border-brand-200 px-3 py-1.5 text-xs font-semibold uppercase tracking-wider hover:bg-brand-100 transition-colors group">
                            {{ $category->name }}
                            <svg class="w-3 h-3 text-brand-400 group-hover:text-brand-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                    @if(isset($author))
                        <a href="{{ route('books.index') }}" class="inline-flex items-center gap-1.5 bg-brand-50 text-brand-700 border border-brand-200 px-3 py-1.5 text-xs font-semibold uppercase tracking-wider hover:bg-brand-100 transition-colors group">
                            {{ $author->name }}
                            <svg class="w-3 h-3 text-brand-400 group-hover:text-brand-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                    @if(request('search'))
                        <a href="{{ route('books.index') }}" class="inline-flex items-center gap-1.5 bg-brand-50 text-brand-700 border border-brand-200 px-3 py-1.5 text-xs font-semibold uppercase tracking-wider hover:bg-brand-100 transition-colors group">
                            "{{ request('search') }}"
                            <svg class="w-3 h-3 text-brand-400 group-hover:text-brand-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                    <a href="{{ route('books.index') }}" class="text-xs font-semibold uppercase tracking-wider text-neutral-400 hover:text-neutral-900 transition-colors">Clear all</a>
                </div>
            @endif

            @if($books->count())
                {{-- Sort --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 pb-6 border-b border-neutral-200 gap-3">
                    <p class="text-sm text-neutral-500">{{ $books->total() }} {{ Str::plural('book', $books->total()) }}</p>
                    <div class="flex items-center gap-2">
                        <label for="sort" class="text-xs font-semibold uppercase tracking-widest text-neutral-500 whitespace-nowrap">Sort by</label>
                        <select id="sort" onchange="window.location.href = this.value"
                            class="input-field text-sm py-1.5 px-3 w-auto">
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'most-visited']) }}" @selected(($sort ?? 'most-visited') === 'most-visited')>Most Visited</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" @selected(($sort ?? '') === 'newest')>Newest</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'oldest']) }}" @selected(($sort ?? '') === 'oldest')>Oldest</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price-asc']) }}" @selected(($sort ?? '') === 'price-asc')>Price: Low to High</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price-desc']) }}" @selected(($sort ?? '') === 'price-desc')>Price: High to Low</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'discount']) }}" @selected(($sort ?? '') === 'discount')>Discount: High to Low</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'title-asc']) }}" @selected(($sort ?? '') === 'title-asc')>Title: A-Z</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'title-desc']) }}" @selected(($sort ?? '') === 'title-desc')>Title: Z-A</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4">
                    @foreach($books as $book)
                        <x-book-card :book="$book" />
                    @endforeach
                </div>
                <div class="mt-10 md:mt-12">
                    {{ $books->appends(['search' => request('search'), 'sort' => $sort ?? 'most-visited'])->links() }}
                </div>
            @else
                <div class="text-center py-20 border border-neutral-200 bg-white">
                    <svg class="w-16 h-16 mx-auto mb-5 text-neutral-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <h3 class="text-xl font-bold mb-2">No books found</h3>
                    <p class="text-neutral-500 mb-6 max-w-md mx-auto">
                        @if(request('search'))
                            We couldn't find any books matching "{{ request('search') }}". Try a different search term.
                        @else
                            No books are available in this category right now. Check back soon!
                        @endif
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        @if(request('search') || isset($category) || isset($author))
                            <a href="{{ route('books.index') }}" class="btn-primary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Clear Filters
                            </a>
                        @endif
                        <a href="{{ route('stationeries.index') }}" class="btn-outline">Browse Stationery</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
