@extends('layouts.frontend')

@section('title', $edition->title . ' - ' . config('app.name'))

@section('content')
<x-breadcrumbs :items="[['label' => 'Book Fairs', 'url' => route('book-fair.index')], ['label' => $edition->title]]" />
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Edition Header --}}
    <div class="border-2 border-black bg-neutral-50 p-8 md:p-12 mb-12">
        @if($edition->is_current)
            <span class="text-xs font-bold uppercase tracking-wider bg-brand-600 text-white px-3 py-1.5">Current Edition</span>
        @endif
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight mt-4">{{ $edition->title }}</h1>
        @if($edition->tagline)
            <p class="text-lg text-neutral-600 mt-3">{{ $edition->tagline }}</p>
        @endif
        <div class="flex flex-wrap gap-6 mt-6 text-sm text-neutral-500">
            @if($edition->start_date)
                <span>{{ $edition->start_date->format('M d, Y') }} @if($edition->end_date) – {{ $edition->end_date->format('M d, Y') }} @endif</span>
            @endif
            @if($edition->venue)
                <span>{{ $edition->venue }}</span>
            @endif
            <span>{{ $books->total() }} {{ Str::plural('book', $books->total()) }}</span>
        </div>
    </div>

    {{-- Books Grid --}}
    @if($books->count())
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($books as $book)
                <x-book-card :book="$book" />
            @endforeach
        </div>
        <div class="mt-12">{{ $books->links() }}</div>
    @else
        <p class="text-neutral-500">No books in this edition yet.</p>
    @endif
</div>
@endsection
