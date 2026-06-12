@extends('layouts.frontend')

@section('title', (request('search') ? 'Search: ' . request('search') . ' - ' . config('app.name') : 'Books - ' . config('app.name')))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
        {{-- Filters Sidebar --}}
        <aside class="lg:col-span-1">
            <div class="border border-neutral-200 p-6 sticky top-24">
                <h3 class="text-sm font-semibold uppercase tracking-widest mb-6">Categories</h3>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('books.index') }}"
                           class="block px-3 py-2 text-sm {{ !request()->route('slug') && !request('search') ? 'bg-black text-white' : 'text-neutral-600 hover:bg-neutral-100' }} transition-colors">
                            All Books
                        </a>
                    </li>
                    @foreach($categories as $cat)
                        <li>
                            <a href="{{ route('category.books', $cat->slug) }}"
                               class="block px-3 py-2 text-sm {{ isset($category) && $category->id === $cat->id ? 'bg-black text-white' : 'text-neutral-600 hover:bg-neutral-100' }} transition-colors">
                                {{ $cat->name }}
                                <span class="text-xs opacity-60">({{ $cat->books_count }})</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        {{-- Books Grid --}}
        <div class="lg:col-span-3">
            @if(request('search'))
                @php $searchTerm = request('search'); @endphp
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-2">Search Results</p>
                <h1 class="section-heading mb-2">"{{ $searchTerm }}"</h1>
                <p class="text-sm text-neutral-500 mb-8">{{ $books->total() }} {{ Str::plural('result', $books->total()) }} found</p>
            @elseif(isset($category))
                <h1 class="section-heading mb-8">{{ $category->name }}</h1>
            @else
                <h1 class="section-heading mb-8">All Books</h1>
            @endif

            @if($books->count())
                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    @foreach($books as $book)
                        <x-book-card :book="$book" />
                    @endforeach
                </div>
                <div class="mt-12">
                    {{ $books->appends(['search' => request('search')])->links() }}
                </div>
            @else
                <p class="text-neutral-500">No books found.</p>
            @endif
        </div>
    </div>
</div>
@endsection
