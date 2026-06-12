@extends('layouts.frontend')

@section('title', config('app.name'))

@section('content')
    {{-- About Bangla Section --}}
    @if(setting('home_about_bangla'))
    <section class="relative border-b border-neutral-200 bg-neutral-900 overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center" @if(setting('home_about_image')) style="background-image: url('{{ asset('storage/' . setting('home_about_image')) }}');" @endif></div>
        <div class="absolute inset-0 bg-black/60"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
            <div class="max-w-4xl mx-auto text-center">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-400 mb-4">{{ setting('home_section_title', 'আনারজো পাবলিকেশনস') }}</p>
                <div class="text-lg md:text-xl text-white/90 leading-relaxed prose prose-invert prose-lg max-w-none">
                    {!! setting('home_about_bangla') !!}
                </div>
                <div class="flex flex-wrap items-center justify-center gap-4 mt-10">
                    <a href="{{ route('about') }}" class="btn-primary bg-white text-black hover:bg-white/90">About Us</a>
                    <a href="{{ route('books.index') }}" class="btn-outline border-white text-white hover:bg-white hover:text-black">Explore Books</a>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Hero Slider --}}
    @if($heroSlides->count())
    <section class="border-b border-neutral-200 py-6 md:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="hero-swiper swiper overflow-hidden rounded-2xl">
                <div class="swiper-wrapper">
                    @foreach($heroSlides as $slide)
                    <div class="swiper-slide">
                        @if($slide->link)
                        <a href="{{ $slide->link }}" class="block">
                        @endif
                            <div class="aspect-[21/9] bg-neutral-100 overflow-hidden">
                                <img src="{{ asset('storage/' . $slide->image) }}" alt="Hero slide" class="w-full h-full object-cover">
                            </div>
                        @if($slide->link)
                        </a>
                        @endif
                    </div>
                    @endforeach
                </div>
                <div class="hero-pagination absolute bottom-4 left-0 right-0 z-20 flex justify-center gap-2"></div>
            </div>
        </div>
    </section>
    @else
    <section class="border-b border-neutral-200 py-6 md:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="aspect-[21/9] bg-neutral-100 flex items-center justify-center rounded-2xl overflow-hidden">
                <span class="text-sm text-neutral-400">No slides available</span>
            </div>
        </div>
    </section>
    @endif

    {{-- Stats Banner --}}
    <section class="border-b border-neutral-200 bg-neutral-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-3 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <p class="text-3xl md:text-4xl font-extrabold tracking-tight">{{ $stats['books'] }}</p>
                    <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mt-1">Published Books</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl md:text-4xl font-extrabold tracking-tight">{{ $stats['authors'] }}</p>
                    <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mt-1">{{ Str::plural('Author', $stats['authors']) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl md:text-4xl font-extrabold tracking-tight">{{ $stats['categories'] }}</p>
                    <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mt-1">Categories</p>
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
                @forelse($categories as $cat)
                    <a href="{{ route('category.books', $cat->slug) }}" class="group border-r border-b border-neutral-200 p-8 text-center hover:bg-black transition-colors duration-300">
                        <p class="text-sm font-semibold uppercase tracking-wider group-hover:text-white transition-colors">{{ $cat->name }}</p>
                        <p class="text-xs text-neutral-400 mt-1 group-hover:text-neutral-300 transition-colors">{{ $cat->books_count }} {{ Str::plural('book', $cat->books_count) }}</p>
                    </a>
                @empty
                    <div class="col-span-full p-8 text-center text-neutral-500 border-r border-b border-neutral-200">
                        No categories yet.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Featured Books --}}
    @if($featuredBooks->count())
    <section class="border-b border-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="flex items-baseline justify-between mb-12">
                <h2 class="section-heading">Featured<br>Books</h2>
                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex items-center gap-2">
                        <button class="featured-prev w-10 h-10 border-2 border-black flex items-center justify-center hover:bg-black hover:text-white transition-colors text-lg font-bold">&larr;</button>
                        <button class="featured-next w-10 h-10 border-2 border-black flex items-center justify-center hover:bg-black hover:text-white transition-colors text-lg font-bold">&rarr;</button>
                    </div>
                    <a href="{{ route('books.index') }}" class="text-sm font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">View All →</a>
                </div>
            </div>
            <div class="featured-books-swiper swiper">
                <div class="swiper-wrapper">
                    @foreach($featuredBooks as $book)
                        <div class="swiper-slide h-auto">
                            <div class="border-2 border-black bg-white h-full flex flex-col group hover:bg-neutral-50 transition-colors">
                                <a href="{{ route('books.show', $book->slug) }}" class="block">
                                    <div class="aspect-[3/4] bg-neutral-100 overflow-hidden">
                                        @if($book->cover_image)
                                            <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center">
                                                <span class="text-3xl text-neutral-300">+</span>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                                <div class="p-3 flex flex-col flex-1">
                                    <a href="{{ route('books.show', $book->slug) }}" class="block flex-1">
                                        @if($book->category)
                                            <span class="text-[10px] font-semibold uppercase tracking-widest text-brand-600">{{ $book->category->name }}</span>
                                        @endif
                                        <h3 class="text-sm font-bold tracking-tight leading-snug mt-1">{{ $book->title }}</h3>
                                        @if($book->author)
                                            <p class="text-[11px] text-neutral-500 mt-1">By {{ $book->author->name }}</p>
                                        @endif
                                    </a>
                                    <div class="pt-3 mt-auto border-t border-neutral-100">
                                        <div class="flex items-center justify-between mb-3">
                                            @if($book->sale_price)
                                                <span class="font-bold text-brand-600 text-sm">৳{{ number_format($book->sale_price) }}</span>
                                                <span class="text-[11px] text-neutral-400 line-through">৳{{ number_format($book->price) }}</span>
                                            @else
                                                <span class="font-bold text-sm">৳{{ number_format($book->price) }}</span>
                                            @endif
                                        </div>
                                        <livewire:add-to-cart-button :book-id="$book->id" :book-title="$book->title" :key="'featured-' . $book->id" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- New Arrivals --}}
    @if($newArrivals->count())
    <section class="border-b border-neutral-200 bg-neutral-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="flex items-baseline justify-between mb-12">
                <h2 class="section-heading">New<br>Arrivals</h2>
                <a href="{{ route('books.index') }}" class="text-sm font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">View All →</a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($newArrivals as $book)
                    <x-book-card :book="$book" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Bestsellers --}}
    @if($bestsellers->count())
    <section class="border-b border-neutral-200">
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

    {{-- CTA Banner --}}
    <section class="border-b border-neutral-200 bg-black">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
            @if (setting('footer_logo'))
                <img src="{{ asset('storage/' . setting('footer_logo')) }}" alt="{{ setting('site_name', config('app.name')) }}" class="h-16 mx-auto mb-6">
            @endif
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-400 mb-4">Publication</p>
            <h2 class="text-3xl md:text-5xl font-extrabold tracking-tight text-white">Publish With Us</h2>
            <p class="text-neutral-400 mt-4 max-w-xl mx-auto leading-relaxed">We welcome manuscripts from aspiring and established authors. Let's bring your story to the world.</p>
            <div class="mt-8">
                <a href="{{ route('publication.submit') }}" class="btn-primary bg-white text-black hover:bg-white/90">Submit Your Manuscript</a>
            </div>
        </div>
    </section>

    {{-- Upcoming Events --}}
    @if($upcomingEvents->count())
    <section class="border-b border-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="flex items-baseline justify-between mb-12">
                <h2 class="section-heading">Upcoming<br>Events</h2>
                <a href="{{ route('events.index') }}" class="text-sm font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">All Events →</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($upcomingEvents as $event)
                    <a href="{{ route('events.show', $event->slug) }}" class="group border-2 border-black p-8 bg-white hover:bg-black transition-colors duration-300">
                        <p class="text-xs font-semibold uppercase tracking-widest text-brand-600 group-hover:text-brand-400">{{ $event->date?->format('M d, Y') }}</p>
                        <h3 class="font-bold text-lg mt-3 group-hover:text-white transition-colors">{{ $event->title }}</h3>
                        @if($event->venue)
                            <p class="text-sm text-neutral-500 mt-2 group-hover:text-neutral-300 transition-colors">{{ $event->venue }}</p>
                        @endif
                        @if($event->description)
                            <p class="text-sm text-neutral-600 mt-3 leading-relaxed group-hover:text-neutral-400 transition-colors">{{ Str::limit(strip_tags($event->description), 120) }}</p>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Author Strip --}}
    @if($authors->count())
    <section class="border-b border-neutral-200 bg-neutral-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="flex items-baseline justify-between mb-12">
                <h2 class="section-heading">Our<br>Authors</h2>
                <a href="{{ route('books.index') }}?author=true" class="text-sm font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">View All →</a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-x-8 gap-y-12">
                @foreach($authors as $author)
                    <a href="{{ route('author.show', $author->slug) }}" class="group">
                        <div class="w-32 h-32 md:w-40 md:h-40 mx-auto mb-5 overflow-hidden rounded-2xl border-2 border-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] group-hover:shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] transition-shadow">
                            @if($author->photo)
                                <img src="{{ asset('storage/' . $author->photo) }}" alt="{{ $author->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-neutral-100 flex items-center justify-center">
                                    <span class="text-3xl font-bold text-neutral-300">{{ substr($author->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="text-center">
                            <p class="font-bold tracking-tight group-hover:text-brand-600 transition-colors">{{ $author->name }}</p>
                            @if($author->bio)
                                <p class="text-xs text-neutral-500 mt-1 line-clamp-2 leading-relaxed">{{ Str::limit($author->bio, 80) }}</p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Newsletter --}}
    <section class="border-b border-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="max-w-2xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">Stay in the Loop</h2>
                <p class="text-neutral-500 mt-3">Subscribe to get notified about new releases, events, and exclusive offers.</p>
                <form method="POST" action="{{ route('home') }}" class="mt-8 flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                    @csrf
                    <input type="email" name="email" placeholder="Your email address" required class="input-field flex-1 text-center sm:text-left">
                    <button type="submit" class="btn-primary btn-sm flex-shrink-0">Subscribe</button>
                </form>
            </div>
        </div>
    </section>

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
