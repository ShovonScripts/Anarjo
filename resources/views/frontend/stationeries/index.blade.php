@extends('layouts.frontend')

@section('title', 'Stationeries - ' . config('app.name'))

@section('content')
<x-breadcrumbs :items="[['label' => 'Stationeries']]" />
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 lg:gap-12">
        {{-- Sidebar --}}
        <aside class="lg:col-span-1">
            <div class="lg:space-y-6 lg:sticky lg:top-24">
                {{-- Mobile toggle --}}
                <div x-data="{ showFilters: false, brandSearch: '' }" class="lg:hidden mb-4">
                    <button @click="showFilters = !showFilters" class="w-full flex items-center justify-between border border-neutral-200 px-4 py-3 text-sm font-semibold uppercase tracking-wider bg-white">
                        <span>Filters</span>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': showFilters }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="showFilters" x-collapse class="mt-4">
                        <div class="border border-neutral-200 p-4">
                            <h3 class="text-xs font-semibold uppercase tracking-widest mb-4">Brands</h3>
                            <input x-model="brandSearch" type="text" placeholder="Search brands..." class="w-full border border-neutral-200 bg-neutral-50 px-3 py-2 text-xs mb-3 placeholder-neutral-400 focus:border-neutral-900 focus:ring-0 focus:outline-none transition-colors">
                            <ul class="space-y-0.5 max-h-60 overflow-y-auto">
                                <li>
                                    <a href="{{ route('stationeries.index') }}"
                                       class="block px-3 py-2 text-sm {{ !isset($brand) ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:bg-neutral-100' }} transition-colors">
                                        All Items
                                    </a>
                                </li>
                                @foreach($brands as $b)
                                    <li x-show="!brandSearch || '{{ strtolower($b->name) }}'.includes(brandSearch.toLowerCase())">
                                        <a href="{{ route('stationeries.brand', $b->slug) }}"
                                           class="block px-3 py-2 text-sm {{ isset($brand) && $brand->id === $b->id ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:bg-neutral-100' }} transition-colors">
                                            {{ $b->name }}
                                            <span class="text-xs opacity-60">({{ $b->stationeries_count }})</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Desktop sidebar --}}
                <div x-data="{ brandSearch: '' }" class="hidden lg:block space-y-6">
                    <div class="border border-neutral-200 p-6">
                        <h3 class="text-sm font-semibold uppercase tracking-widest mb-6">Brands</h3>
                        <input x-model="brandSearch" type="text" placeholder="Search brands..." class="w-full border border-neutral-200 bg-neutral-50 px-3 py-2 text-xs mb-4 placeholder-neutral-400 focus:border-neutral-900 focus:ring-0 focus:outline-none transition-colors">
                        <ul class="space-y-1 max-h-72 overflow-y-auto">
                            <li>
                                <a href="{{ route('stationeries.index') }}"
                                   class="block px-3 py-2 text-sm {{ !isset($brand) ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:bg-neutral-100' }} transition-colors">
                                    All Items
                                </a>
                            </li>
                            @foreach($brands as $b)
                                <li x-show="!brandSearch || '{{ strtolower($b->name) }}'.includes(brandSearch.toLowerCase())">
                                    <a href="{{ route('stationeries.brand', $b->slug) }}"
                                       class="block px-3 py-2 text-sm {{ isset($brand) && $brand->id === $b->id ? 'bg-neutral-900 text-white' : 'text-neutral-600 hover:bg-neutral-100' }} transition-colors">
                                        {{ $b->name }}
                                        <span class="text-xs opacity-60">({{ $b->stationeries_count }})</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Items Grid --}}
        <div class="lg:col-span-3">
            @php $hasActiveFilter = isset($brand); @endphp

            @if(isset($brand))
                <h1 class="section-heading mb-6 md:mb-8">{{ $brand->name }}</h1>
            @else
                <h1 class="section-heading mb-6 md:mb-8">All Stationeries</h1>
            @endif

            {{-- Active Filter Chips --}}
            @if($hasActiveFilter)
                <div class="flex flex-wrap items-center gap-2 mb-6 pb-6 border-b border-neutral-200">
                    @if(isset($brand))
                        <a href="{{ route('stationeries.index') }}" class="inline-flex items-center gap-1.5 bg-brand-50 text-brand-700 border border-brand-200 px-3 py-1.5 text-xs font-semibold uppercase tracking-wider hover:bg-brand-100 transition-colors group">
                            {{ $brand->name }}
                            <svg class="w-3 h-3 text-brand-400 group-hover:text-brand-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                    <a href="{{ route('stationeries.index') }}" class="text-xs font-semibold uppercase tracking-wider text-neutral-400 hover:text-neutral-900 transition-colors">Clear all</a>
                </div>
            @endif

            @if($items->count())
                {{-- Sort --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 pb-6 border-b border-neutral-200 gap-3">
                    <p class="text-sm text-neutral-500">{{ $items->total() }} {{ Str::plural('item', $items->total()) }}</p>
                    <div class="flex items-center gap-2">
                        <label for="sort" class="text-xs font-semibold uppercase tracking-widest text-neutral-500 whitespace-nowrap">Sort by</label>
                        <select id="sort" onchange="window.location.href = this.value"
                            class="input-field text-sm py-1.5 px-3 w-auto">
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" @selected(($sort ?? 'newest') === 'newest')>Newest</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'oldest']) }}" @selected(($sort ?? '') === 'oldest')>Oldest</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price-asc']) }}" @selected(($sort ?? '') === 'price-asc')>Price: Low to High</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'price-desc']) }}" @selected(($sort ?? '') === 'price-desc')>Price: High to Low</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'discount']) }}" @selected(($sort ?? '') === 'discount')>Discount: High to Low</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'title-asc']) }}" @selected(($sort ?? '') === 'title-asc')>Title: A-Z</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'title-desc']) }}" @selected(($sort ?? '') === 'title-desc')>Title: Z-A</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6">
                    @foreach($items as $item)
                        <x-stationery-card :item="$item" />
                    @endforeach
                </div>
                <div class="mt-10 md:mt-12">
                    {{ $items->appends(['sort' => $sort ?? 'newest'])->links() }}
                </div>
            @else
                <div class="text-center py-20 border border-neutral-200 bg-white">
                    <svg class="w-16 h-16 mx-auto mb-5 text-neutral-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    <h3 class="text-xl font-bold mb-2">No stationery found</h3>
                    <p class="text-neutral-500 mb-6 max-w-md mx-auto">
                        @if(isset($brand))
                            No stationery items available from this brand right now.
                        @else
                            No stationery items are available yet. Check back soon!
                        @endif
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        @if(isset($brand))
                            <a href="{{ route('stationeries.index') }}" class="btn-primary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Clear Filter
                            </a>
                        @endif
                        <a href="{{ route('books.index') }}" class="btn-outline">Browse Books</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
