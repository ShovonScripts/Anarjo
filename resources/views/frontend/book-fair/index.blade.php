@extends('layouts.frontend')

@section('title', 'Book Fairs - ' . config('app.name'))

@section('content')
<x-breadcrumbs :items="[['label' => 'Book Fairs']]" />
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="section-heading mb-10">Book Fair Editions</h1>

    @if($editions->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($editions as $edition)
                <a href="{{ route('book-fair.show', $edition->slug) }}" class="group border-2 border-black bg-white hover:bg-black transition-colors duration-300">
                    <div class="aspect-[16/9] bg-neutral-100 overflow-hidden">
                        @if($edition->image)
                            <img src="{{ asset('storage/' . $edition->image) }}" alt="{{ $edition->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="text-3xl text-neutral-300">+</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-2">
                            @if($edition->is_current)
                                <span class="text-[10px] font-bold uppercase tracking-wider bg-brand-600 text-white px-2 py-1">Current</span>
                            @endif
                            @if($edition->start_date)
                                <span class="text-xs text-neutral-500 group-hover:text-neutral-400 transition-colors">{{ $edition->start_date->format('M d, Y') }}</span>
                            @endif
                        </div>
                        <h3 class="font-bold text-lg group-hover:text-white transition-colors">{{ $edition->title }}</h3>
                        @if($edition->tagline)
                            <p class="text-sm text-neutral-600 mt-2 group-hover:text-neutral-400 transition-colors">{{ $edition->tagline }}</p>
                        @endif
                        <p class="text-xs text-neutral-500 mt-3 group-hover:text-neutral-400 transition-colors">{{ $edition->books_count }} {{ Str::plural('book', $edition->books_count) }}</p>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mt-12">{{ $editions->links() }}</div>
    @else
        <p class="text-neutral-500">No book fair editions yet.</p>
    @endif
</div>
@endsection
