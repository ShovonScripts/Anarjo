@extends('layouts.frontend')

@section('title', $author->name . ' - ' . config('app.name'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex flex-col md:flex-row gap-12 mb-16">
        <div class="w-40 h-40 flex-shrink-0 overflow-hidden bg-neutral-200 border-2 border-black">
            @if($author->photo)
                <img src="{{ asset('storage/' . $author->photo) }}" alt="{{ $author->name }}" class="w-full h-full object-cover">
            @endif
        </div>
        <div class="flex-1">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-3">Author</p>
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight">{{ $author->name }}</h1>
            @if($author->bio)
                <p class="text-neutral-600 leading-relaxed mt-6 max-w-2xl">{{ $author->bio }}</p>
            @endif
            <div class="flex gap-6 mt-6">
                @if($author->facebook)
                    <a href="{{ $author->facebook }}" class="text-sm font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">Facebook</a>
                @endif
                @if($author->website)
                    <a href="{{ $author->website }}" class="text-sm font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">Website</a>
                @endif
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
