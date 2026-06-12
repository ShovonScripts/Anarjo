@extends('layouts.frontend')

@section('title', $author->name . ' - ' . config('app.name'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex flex-col md:flex-row items-center md:items-start gap-10 md:gap-16 mb-20">
        <div class="w-48 h-48 md:w-56 md:h-56 flex-shrink-0 overflow-hidden rounded-2xl border-2 border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)]">
            @if($author->photo)
                <img src="{{ asset('storage/' . $author->photo) }}" alt="{{ $author->name }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-neutral-100 flex items-center justify-center">
                    <span class="text-5xl font-bold text-neutral-300">{{ substr($author->name, 0, 1) }}</span>
                </div>
            @endif
        </div>
        <div class="flex-1 text-center md:text-left">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-3">Author</p>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">{{ $author->name }}</h1>
            @if($author->bio)
                <div class="mt-6 max-w-3xl">
                    <p class="text-neutral-600 text-lg leading-relaxed">{{ $author->bio }}</p>
                </div>
            @endif
            <div class="flex flex-wrap items-center gap-6 mt-8 text-sm text-neutral-500">
                <span>{{ $books->total() }} {{ $books->total() === 1 ? 'Book' : 'Books' }}</span>
            </div>
        </div>
    </div>

    <h2 class="section-heading mb-10">Books by {{ $author->name }}</h2>
    @if($books->count())
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($books as $book)
                <x-book-card :book="$book" />
            @endforeach
        </div>
        <div class="mt-12">{{ $books->links() }}</div>
    @else
        <p class="text-neutral-500">No books published yet.</p>
    @endif
</div>
@endsection
