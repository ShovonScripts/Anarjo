@extends('layouts.frontend')

@section('title', 'Search: ' . $query . ' - ' . config('app.name'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="mb-12">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-2">Search Results</p>
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight">
            Results for "<span class="text-brand-600">{{ $query }}</span>"
        </h1>
        @if($totalResults > 0)
            <p class="text-neutral-500 mt-3">{{ $totalResults }} {{ Str::plural('result', $totalResults) }} found</p>
        @endif
    </div>

    @if($totalResults === 0)
        <div class="border-2 border-black p-12 text-center max-w-lg mx-auto">
            <div class="text-5xl mb-4 font-bold text-neutral-300">?</div>
            <p class="text-lg font-bold tracking-tight mb-2">No results found</p>
            <p class="text-sm text-neutral-500">Try searching with different keywords.</p>
        </div>
    @endif

    @if($books->count())
    <section class="mb-16">
        <div class="flex items-baseline justify-between mb-8">
            <h2 class="text-2xl font-bold tracking-tight">Books</h2>
            <a href="{{ route('books.index') }}?search={{ urlencode($query) }}" class="text-sm font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">View All →</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($books as $book)
                <x-book-card :book="$book" />
            @endforeach
        </div>
    </section>
    @endif

    @if($authors->count())
    <section class="mb-16">
        <div class="flex items-baseline justify-between mb-8">
            <h2 class="text-2xl font-bold tracking-tight">Authors</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($authors as $author)
                <a href="{{ route('author.show', $author->slug) }}" class="group border-2 border-black p-6 text-center hover:bg-neutral-50 transition-colors">
                    <div class="w-20 h-20 mx-auto mb-4 overflow-hidden rounded-full border-2 border-black">
                        @if($author->photo)
                            <img src="{{ asset('storage/' . $author->photo) }}" alt="{{ $author->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-neutral-100 flex items-center justify-center">
                                <span class="text-xl font-bold text-neutral-300">{{ substr($author->name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    <p class="font-bold tracking-tight group-hover:text-brand-600 transition-colors">{{ $author->name }}</p>
                </a>
            @endforeach
        </div>
    </section>
    @endif

    @if($blogs->count())
    <section class="mb-16">
        <div class="flex items-baseline justify-between mb-8">
            <h2 class="text-2xl font-bold tracking-tight">Blog Posts</h2>
            <a href="{{ route('blog.index') }}?search={{ urlencode($query) }}" class="text-sm font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">View All →</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($blogs as $blog)
                <a href="{{ route('blog.show', $blog->slug) }}" class="group border-2 border-black p-6 hover:bg-neutral-50 transition-colors">
                    <span class="text-xs uppercase tracking-widest text-neutral-400">{{ $blog->published_at?->format('M d, Y') }}</span>
                    <h3 class="font-bold text-lg mt-2 group-hover:text-brand-600 transition-colors">{{ $blog->title }}</h3>
                    <p class="text-sm text-neutral-600 mt-2 leading-relaxed">{{ Str::limit(strip_tags($blog->content), 150) }}</p>
                </a>
            @endforeach
        </div>
    </section>
    @endif

    @if($events->count())
    <section class="mb-16">
        <div class="flex items-baseline justify-between mb-8">
            <h2 class="text-2xl font-bold tracking-tight">Events</h2>
            <a href="{{ route('events.index') }}?search={{ urlencode($query) }}" class="text-sm font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">View All →</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($events as $event)
                <a href="{{ route('events.show', $event->slug) }}" class="group border-2 border-black p-6 hover:bg-neutral-50 transition-colors">
                    <span class="text-xs uppercase tracking-widest text-brand-600">{{ $event->date?->format('M d, Y') }}</span>
                    <h3 class="font-bold text-lg mt-2 group-hover:text-brand-600 transition-colors">{{ $event->title }}</h3>
                    @if($event->description)
                        <p class="text-sm text-neutral-600 mt-2 leading-relaxed">{{ Str::limit(strip_tags($event->description), 150) }}</p>
                    @endif
                </a>
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection
