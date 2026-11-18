@extends('layouts.frontend')

@section('title', config('app.name'))

@section('content')
    @if(session('success'))
        <div class="bg-black text-white text-center py-4 text-sm font-semibold uppercase tracking-wider">
            Thank you for subscribing to our newsletter!
        </div>
    @endif
    {{-- About Bangla Section --}}
    @if(setting('home_about_bangla'))
    <section class="relative border-b border-neutral-200 bg-neutral-900 overflow-hidden reveal">
        <div class="absolute inset-0 bg-cover bg-center ken-burns" @if(setting('home_about_image')) style="background-image: url('{{ asset('storage/' . setting('home_about_image')) }}');" @endif></div>
        <div class="absolute inset-0 bg-gradient-to-b from-neutral-900/70 via-neutral-900/30 to-neutral-950/80"></div>
        <div class="absolute inset-0 vignette"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-36">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="about-typewriter font-serif text-4xl sm:text-5xl md:text-6xl font-bold tracking-tight leading-tight mb-5 min-h-[1.15em]">
                    <span class="typed-text text-white" data-text="{{ setting('home_section_title', config('app.name')) }}"></span><span class="typed-cursor text-brand-400" aria-hidden="true">|</span>
                </h1>
                <div class="mx-auto w-10 h-0.5 bg-brand-600 mb-8"></div>
                <div class="text-base sm:text-lg md:text-2xl font-light leading-relaxed text-white/90 max-w-3xl mx-auto prose-headings:text-white/90 prose-headings:font-light prose-a:text-brand-400 prose-strong:text-white/90">
                    {!! setting('home_about_bangla') !!}
                </div>
                <div class="flex flex-wrap items-center justify-center gap-4 mt-10">
                    <a href="{{ route('about') }}" class="btn-primary bg-white text-neutral-900 hover:bg-white/90">About Us</a>
                    <a href="{{ route('books.index') }}" class="btn-outline border-white text-white hover:bg-white hover:text-neutral-900">Explore Books</a>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Hero: Featured Book Promo + Slider --}}
    @if($heroSlides->count())
    <section class="border-b border-neutral-200">
        <div class="px-4 sm:px-6 lg:px-10 xl:px-14 py-6 md:py-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 md:gap-6">
                {{-- Left: Featured Books Rotator --}}
                <div class="order-2 lg:order-1 relative bg-neutral-900 text-white flex flex-col overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-brand-700/40 via-neutral-900/10 to-transparent"></div>
                    <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-brand-600/20 rounded-full blur-3xl"></div>

                    @if($featuredBooks->count())
                        @php $promoBooks = $featuredBooks->take(6); @endphp
                        <div class="relative flex items-center justify-between px-5 sm:px-6 md:px-8 pt-5 sm:pt-6 md:pt-8">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-400">Featured Book</p>
                            <div class="flex items-center gap-2">
                                <button class="featured-book-prev w-8 h-8 border border-white/30 flex items-center justify-center hover:bg-white hover:text-neutral-900 transition-colors text-sm font-bold" aria-label="Previous book">&larr;</button>
                                <button class="featured-book-next w-8 h-8 border border-white/30 flex items-center justify-center hover:bg-white hover:text-neutral-900 transition-colors text-sm font-bold" aria-label="Next book">&rarr;</button>
                            </div>
                        </div>

                        <div class="featured-book-swiper swiper relative flex-1 min-h-0 w-full" role="region" aria-roledescription="carousel" aria-label="Featured book carousel">
                            <div class="swiper-wrapper">
                                @foreach($promoBooks as $book)
                                <div class="swiper-slide h-auto">
                                    <a href="{{ route('books.show', $book->slug) }}" class="flex flex-col items-center text-center h-full px-5 sm:px-6 md:px-8 pt-5 sm:pt-6 md:pt-8 pb-8 sm:pb-10 md:pb-12 group">
                                        <div class="relative mb-4 sm:mb-6 shrink-0">
                                            <div class="absolute inset-0 bg-white/10 rotate-[-5deg] rounded-sm"></div>
                                            <div class="relative w-32 sm:w-36 md:w-44 aspect-[3/4] bg-neutral-100 overflow-hidden shadow-2xl shadow-black/50 transition-transform duration-500 group-hover:-translate-y-1">
                                                @if($book->cover_image)
                                                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover" loading="lazy">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-neutral-700 to-neutral-800">
                                                        <svg class="w-10 h-10 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <h2 class="text-base md:text-xl font-serif font-bold leading-tight line-clamp-2">{{ $book->title }}</h2>
                                        @if($book->author)
                                            <p class="text-xs text-white/60 mt-1.5">By {{ $book->author->name }}</p>
                                        @endif
                                        <div class="mt-2 md:mt-3">
                                            @if($book->sale_price)
                                                <span class="font-bold text-brand-400">৳{{ number_format($book->sale_price) }}</span>
                                                <span class="text-xs text-white/40 line-through ml-2">৳{{ number_format($book->price) }}</span>
                                            @else
                                                <span class="font-bold">৳{{ number_format($book->price) }}</span>
                                            @endif
                                        </div>
                                        <span class="inline-flex items-center gap-2 mt-5 md:mt-6 bg-white text-neutral-900 px-5 py-2.5 text-xs font-semibold uppercase tracking-wider hover:bg-white/90 transition-all duration-300">Buy Now →</span>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                            <div class="featured-book-pagination absolute bottom-3 left-0 right-0 z-20 flex justify-center gap-1.5"></div>
                        </div>
                    @else
                        <div class="relative flex flex-col justify-center flex-1 p-8 md:p-10">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-400 mb-3">{{ setting('site_name', config('app.name')) }}</p>
                            <h2 class="text-2xl md:text-3xl font-serif font-bold leading-tight">{{ setting('site_name', config('app.name')) }}</h2>
                            <p class="text-sm text-white/70 mt-3">{{ setting('site_tagline', 'Bangladesh\'s leading publication house delivering knowledge since inception.') }}</p>
                            <div class="flex flex-wrap gap-3 mt-8">
                                <a href="{{ route('about') }}" class="btn-primary bg-white text-neutral-900 hover:bg-white/90">About Us</a>
                                <a href="{{ route('books.index') }}" class="btn-outline border-white text-white hover:bg-white hover:text-neutral-900">Explore Books</a>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Right: Hero Slider --}}
                <div class="order-1 lg:order-2 lg:col-span-2">
                    <div class="hero-swiper swiper overflow-hidden relative h-full" role="region" aria-roledescription="carousel" aria-label="Hero carousel">
                        <div class="swiper-wrapper">
                            @foreach($heroSlides as $slide)
                            <div class="swiper-slide relative overflow-hidden">
                                <div class="aspect-[4/3] sm:aspect-[16/9] lg:h-full lg:w-full bg-neutral-100 overflow-hidden flex items-center justify-center">
                                    <img src="{{ asset('storage/' . $slide->image) }}" alt="{{ $slide->title ?? 'Hero slide' }}" class="min-w-full min-h-full object-cover object-center swiper-lazy" loading="lazy">
                                </div>
                                <div class="absolute inset-0 bg-gradient-to-t from-neutral-900/60 via-transparent to-transparent"></div>
                                @if($slide->title || $slide->subtitle || $slide->description)
                                <div class="absolute inset-0 flex items-end">
                                    <div class="px-5 sm:px-6 md:px-10 pb-6 sm:pb-8 md:pb-12 w-full">
                                        <div class="max-w-xl">
                                            @if($slide->subtitle)
                                                <p class="text-xs md:text-sm font-semibold uppercase tracking-[0.2em] text-white/70 mb-2 md:mb-3">{{ $slide->subtitle }}</p>
                                            @endif
                                            @if($slide->title)
                                                <h2 class="text-xl sm:text-2xl md:text-4xl font-serif font-bold text-white leading-tight">{{ $slide->title }}</h2>
                                            @endif
                                            @if($slide->description)
                                                <p class="text-sm md:text-base text-white/80 mt-2 md:mt-3 leading-relaxed max-w-lg line-clamp-3 md:line-clamp-none">{{ $slide->description }}</p>
                                            @endif
                                            @if($slide->link && $slide->link_text)
                                                <a href="{{ $slide->link }}" class="inline-flex items-center gap-2 mt-4 md:mt-6 bg-white text-neutral-900 px-5 py-2.5 md:px-6 md:py-3 text-xs md:text-sm font-semibold uppercase tracking-wider hover:bg-white/90 transition-all duration-300">
                                                    {{ $slide->link_text }}
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        <div class="hero-pagination absolute bottom-4 left-0 right-0 z-20 flex justify-center gap-2"></div>
                    </div>
                </div>

                {{-- Right: Featured Blog --}}
                <div class="order-3 relative bg-neutral-900 text-white flex flex-col overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-bl from-brand-700/30 via-neutral-900/10 to-transparent"></div>
                    <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-brand-600/20 rounded-full blur-3xl"></div>

                    @if($latestBlogs->count())
                        @php $featuredPost = $latestBlogs->first(); $recentPosts = $latestBlogs->slice(1, 2); @endphp
                        <div class="relative flex items-center justify-between px-5 sm:px-6 md:px-8 pt-5 sm:pt-6 md:pt-8">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-400">From the Blog</p>
                            <a href="{{ route('blog.index') }}" class="text-xs font-semibold uppercase tracking-wider text-white/60 hover:text-white transition-colors">View All</a>
                        </div>

                        <div class="relative flex flex-col flex-1 min-h-0 px-5 sm:px-6 md:px-8 pt-4 sm:pt-5 md:pt-6 pb-5 sm:pb-6 md:pb-8">
                            <a href="{{ route('blog.show', $featuredPost->slug) }}" class="group">
                                <div class="relative aspect-[16/10] bg-neutral-800 overflow-hidden mb-4">
                                    @if($featuredPost->featured_image)
                                        <img src="{{ asset('storage/' . $featuredPost->featured_image) }}" alt="{{ $featuredPost->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-neutral-700 to-neutral-800">
                                            <svg class="w-10 h-10 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                        </div>
                                    @endif
                                </div>
                                <p class="text-[11px] uppercase tracking-widest text-white/50 mb-1.5">
                                    {{ $featuredPost->category?->name ?? 'Publication' }} · {{ $featuredPost->published_at?->format('M d, Y') }}
                                </p>
                                <h3 class="text-base md:text-lg font-serif font-bold leading-snug line-clamp-2 group-hover:text-brand-400 transition-colors">{{ $featuredPost->title }}</h3>
                            </a>

                            @if($recentPosts->count())
                                <div class="mt-5 pt-5 border-t border-white/10 space-y-4">
                                    @foreach($recentPosts as $post)
                                        <a href="{{ route('blog.show', $post->slug) }}" class="flex gap-4 items-center group">
                                            <div class="w-16 h-16 shrink-0 bg-neutral-800 overflow-hidden">
                                                @if($post->featured_image)
                                                    <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-neutral-700 to-neutral-800">
                                                        <svg class="w-5 h-5 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-semibold leading-snug line-clamp-2 group-hover:text-brand-400 transition-colors">{{ $post->title }}</h4>
                                                <p class="text-[11px] uppercase tracking-widest text-white/40 mt-1">{{ $post->published_at?->format('M d, Y') }}</p>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="relative flex flex-col justify-center flex-1 p-8 md:p-10">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-400 mb-3">From the Blog</p>
                            <h2 class="text-2xl font-serif font-bold leading-tight">Stories &amp; Updates</h2>
                            <p class="text-sm text-white/70 mt-3">News, author interviews and reading lists are on the way.</p>
                            <a href="{{ route('blog.index') }}" class="btn-primary bg-white text-neutral-900 hover:bg-white/90 mt-8 self-start">Visit Blog</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @else
    <section class="border-b border-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 md:py-8">
            <div class="aspect-[16/9] md:aspect-[21/8] bg-neutral-100 flex items-center justify-center overflow-hidden">
                <span class="text-sm text-neutral-500">No slides available</span>
            </div>
        </div>
    </section>
    @endif

    {{-- Marketing Ads --}}
    @php
        $boxes = [];
        for ($i = 1; $i <= 4; $i++) {
            $img = setting('branding_box_' . $i . '_image', '');
            if ($img) {
                $boxes[] = [
                    'image' => $img,
                    'title' => setting('branding_box_' . $i . '_title', ''),
                    'link' => setting('branding_box_' . $i . '_link', ''),
                ];
            }
        }
    @endphp
    @if(count($boxes))
    <section class="border-b border-neutral-200 bg-white">
        {{-- Mobile: swipeable ad carousel --}}
        <div class="md:hidden">
            <div class="branding-mobile-swiper swiper relative overflow-hidden py-4 pb-10">
                <div class="swiper-wrapper">
                    @foreach($boxes as $box)
                        <div class="swiper-slide h-48 sm:h-64">
                            @if($box['link'])
                                <a href="{{ $box['link'] }}" target="_blank" rel="noopener noreferrer" class="block w-full h-full" aria-label="{{ $box['title'] ?: 'Advertisement' }}">
                                    <img src="{{ asset('storage/' . $box['image']) }}" alt="{{ $box['title'] ?: 'Advertisement' }}" class="w-full h-full object-contain" loading="lazy">
                                </a>
                            @else
                                <img src="{{ asset('storage/' . $box['image']) }}" alt="{{ $box['title'] ?: 'Advertisement' }}" class="w-full h-full object-contain" loading="lazy">
                            @endif
                        </div>
                    @endforeach
                </div>
                @if(count($boxes) > 1)
                    <div class="branding-mobile-pagination swiper-pagination z-10"></div>
                @endif
            </div>
        </div>

        {{-- Desktop: 4-up grid filling the space --}}
        <div class="hidden md:block">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($boxes as $box)
                        @if($box['link'])
                            <a href="{{ $box['link'] }}" target="_blank" rel="noopener noreferrer" class="group overflow-hidden bg-neutral-50" aria-label="{{ $box['title'] ?: 'Advertisement' }}">
                                <img src="{{ asset('storage/' . $box['image']) }}" alt="{{ $box['title'] ?: 'Advertisement' }}" class="w-full h-auto object-contain transition-transform duration-500 group-hover:scale-[1.03]" loading="lazy">
                            </a>
                        @else
                            <div class="overflow-hidden bg-neutral-50">
                                <img src="{{ asset('storage/' . $box['image']) }}" alt="{{ $box['title'] ?: 'Advertisement' }}" class="w-full h-auto object-contain" loading="lazy">
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif





    {{-- Book Fair Edition Section --}}
    @if($currentFairEdition && $fairEditionBooks->count())
    <section class="border-b border-neutral-200 bg-neutral-900 text-white reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-20">
            <div class="flex items-baseline justify-between mb-8 md:mb-12">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-400 mb-2">Current Edition</p>
                    <h2 class="section-heading text-white">{{ $currentFairEdition->title }}</h2>
                    @if($currentFairEdition->tagline)
                        <p class="text-neutral-400 mt-2">{{ $currentFairEdition->tagline }}</p>
                    @endif
                </div>
                <a href="{{ route('book-fair.show', $currentFairEdition->slug) }}" class="text-sm font-semibold uppercase tracking-wider text-white hover:text-brand-400 transition-colors">View All →</a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach($fairEditionBooks as $book)
                    <x-book-card :book="$book" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Featured Books --}}
    @if($featuredBooks->count())
    <section class="border-b border-neutral-200 reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-20">
            <div class="flex items-baseline justify-between mb-8 md:mb-12">
                <h2 class="section-heading">Featured Books</h2>
                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex items-center gap-2">
                        <button class="featured-prev w-10 h-10 border-2 border-neutral-900 flex items-center justify-center hover:bg-neutral-900 hover:text-white transition-colors text-lg font-bold" aria-label="Previous featured books">&larr;</button>
                        <button class="featured-next w-10 h-10 border-2 border-neutral-900 flex items-center justify-center hover:bg-neutral-900 hover:text-white transition-colors text-lg font-bold" aria-label="Next featured books">&rarr;</button>
                    </div>
                    <a href="{{ route('books.index') }}" class="text-sm font-semibold uppercase tracking-wider text-neutral-900 hover:text-brand-600 transition-colors">View All →</a>
                </div>
            </div>
            <div class="featured-books-swiper swiper" role="region" aria-roledescription="carousel" aria-label="Featured books carousel">
                <div class="swiper-wrapper">
                    @foreach($featuredBooks as $book)
                        <div class="swiper-slide h-auto">
                            <div class="border-2 border-neutral-900 bg-white h-full flex flex-col group hover:bg-neutral-50 transition-colors">
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
                                        @if($book->categories->count())
                                            <span class="text-[10px] font-semibold uppercase tracking-widest text-brand-600">{{ $book->categories->first()->name }}</span>
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

    {{-- Popular Books --}}
    @if($popularBooks->count())
    <section class="border-b border-neutral-200 reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-20">
            <div class="flex items-baseline justify-between mb-8 md:mb-12">
                <h2 class="section-heading">Popular Books</h2>
                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex items-center gap-2">
                        <button class="popular-prev w-10 h-10 border-2 border-neutral-900 flex items-center justify-center hover:bg-neutral-900 hover:text-white transition-colors text-lg font-bold" aria-label="Previous popular books">&larr;</button>
                        <button class="popular-next w-10 h-10 border-2 border-neutral-900 flex items-center justify-center hover:bg-neutral-900 hover:text-white transition-colors text-lg font-bold" aria-label="Next popular books">&rarr;</button>
                    </div>
                    <a href="{{ route('books.index') }}" class="text-sm font-semibold uppercase tracking-wider text-neutral-900 hover:text-brand-600 transition-colors">View All →</a>
                </div>
            </div>
            <div class="popular-books-swiper swiper" role="region" aria-roledescription="carousel" aria-label="Popular books carousel">
                <div class="swiper-wrapper">
                    @foreach($popularBooks as $book)
                        <div class="swiper-slide h-auto">
                            <div class="border-2 border-neutral-900 bg-white h-full flex flex-col group hover:bg-neutral-50 transition-colors">
                                <a href="{{ route('books.show', $book->slug) }}" class="block">
                                    <div class="aspect-[3/4] bg-neutral-100 overflow-hidden relative">
                                        <span class="absolute top-2 left-2 bg-neutral-900 text-white text-[10px] font-bold uppercase tracking-wider px-2 py-1 z-10">{{ number_format($book->views) }} views</span>
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
                                        @if($book->categories->count())
                                            <span class="text-[10px] font-semibold uppercase tracking-widest text-brand-600">{{ $book->categories->first()->name }}</span>
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
                                        <livewire:add-to-cart-button :book-id="$book->id" :book-title="$book->title" :key="'popular-' . $book->id" />
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

    {{-- Discounted Books --}}
    @if($discountedBooks->count())
    <section class="border-b border-neutral-200 bg-neutral-100/50 reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-20">
            <div class="flex items-baseline justify-between mb-8 md:mb-12">
                <h2 class="section-heading">Discounted Books</h2>
                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex items-center gap-2">
                        <button class="discounted-prev w-10 h-10 border-2 border-neutral-900 flex items-center justify-center hover:bg-neutral-900 hover:text-white transition-colors text-lg font-bold" aria-label="Previous discounted books">&larr;</button>
                        <button class="discounted-next w-10 h-10 border-2 border-neutral-900 flex items-center justify-center hover:bg-neutral-900 hover:text-white transition-colors text-lg font-bold" aria-label="Next discounted books">&rarr;</button>
                    </div>
                    <a href="{{ route('books.index') }}" class="text-sm font-semibold uppercase tracking-wider text-neutral-900 hover:text-brand-600 transition-colors">View All →</a>
                </div>
            </div>
            <div class="discounted-books-swiper swiper" role="region" aria-roledescription="carousel" aria-label="Discounted books carousel">
                <div class="swiper-wrapper">
                    @foreach($discountedBooks as $book)
                        <div class="swiper-slide h-auto">
                            <div class="border-2 border-neutral-900 bg-white h-full flex flex-col group hover:bg-neutral-50 transition-colors">
                                <a href="{{ route('books.show', $book->slug) }}" class="block">
                                    <div class="aspect-[3/4] bg-neutral-100 overflow-hidden relative">
                                        @if($book->sale_price && $book->price > $book->sale_price)
                                            @php $discountPercent = round((($book->price - $book->sale_price) / $book->price) * 100); @endphp
                                            <span class="absolute top-2 left-2 bg-brand-600 text-white text-[10px] font-bold uppercase tracking-wider px-2 py-1 z-10">{{ $discountPercent }}% OFF</span>
                                        @endif
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
                                        @if($book->categories->count())
                                            <span class="text-[10px] font-semibold uppercase tracking-widest text-brand-600">{{ $book->categories->first()->name }}</span>
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
                                        <livewire:add-to-cart-button :book-id="$book->id" :book-title="$book->title" :key="'discounted-' . $book->id" />
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

    {{-- Our Writers --}}
    @if($authors->count())
    <section class="border-b border-neutral-200 bg-neutral-900 text-white reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-20">
            <div class="flex items-baseline justify-between mb-8 md:mb-12">
                <h2 class="section-heading text-white">Our Writers</h2>
                <span class="hidden sm:inline text-xs md:text-sm font-semibold uppercase tracking-wider text-white/50">{{ $authors->count() }} Writers</span>
            </div>
            <div class="writers-marquee">
                <div class="writers-marquee-track">
                    @for($copy = 1; $copy <= 2; $copy++)
                        @foreach($authors as $author)
                            <a href="{{ route('author.show', $author->slug) }}" class="group block w-40 sm:w-52 mr-4 sm:mr-6 shrink-0 border border-white/10 bg-white/[0.03] hover:bg-white/[0.08] hover:border-brand-400/60 transition-all duration-300 overflow-hidden">
                                <div class="aspect-square overflow-hidden relative">
                                    @if($author->photo)
                                        <img src="{{ asset('storage/' . $author->photo) }}" alt="{{ $author->name }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 scale-100 group-hover:scale-105 transition-all duration-500" loading="lazy">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-neutral-700 to-neutral-800">
                                            <span class="text-5xl font-serif font-bold text-neutral-400">{{ mb_substr($author->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-neutral-900/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </div>
                                <div class="p-3 sm:p-4">
                                    <h3 class="font-serif font-bold text-sm sm:text-base leading-snug line-clamp-1 group-hover:text-brand-400 transition-colors">{{ $author->name }}</h3>
                                    <p class="text-xs text-white/50 mt-1">{{ $author->books_count }} {{ $author->books_count === 1 ? 'Book' : 'Books' }}</p>
                                </div>
                            </a>
                        @endforeach
                    @endfor
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- New Arrivals --}}
    @if($newArrivals->count())
    <section class="border-b border-neutral-200 bg-neutral-100/50 reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-20">
            <div class="flex items-baseline justify-between mb-8 md:mb-12">
                <h2 class="section-heading">New Arrivals</h2>
                <a href="{{ route('books.index') }}" class="text-sm font-semibold uppercase tracking-wider text-neutral-900 hover:text-brand-600 transition-colors">View All →</a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                @foreach($newArrivals as $book)
                    <x-book-card :book="$book" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Newsletter --}}
    <section class="border-b border-neutral-200 reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-20">
            <div class="max-w-2xl mx-auto text-center">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold tracking-tight">Stay in the Loop</h2>
                <p class="text-neutral-500 mt-3">Subscribe to get notified about new releases, events, and exclusive offers.</p>
                <form method="POST" action="{{ route('newsletter.subscribe') }}" class="mt-8 flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                    @csrf
                    <input type="email" name="email" placeholder="Your email address" required class="input-field flex-1 text-center sm:text-left">
                    <button type="submit" class="btn-primary btn-sm flex-shrink-0">Subscribe</button>
                </form>
            </div>
        </div>
    </section>

    {{-- Blog Section --}}
    @if($latestBlogs->count())
    <section class="bg-neutral-100/50 reveal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 md:py-20">
            <div class="flex items-baseline justify-between mb-8 md:mb-12">
                <h2 class="section-heading">Latest from the Blog</h2>
                <a href="{{ route('blog.index') }}" class="text-sm font-semibold uppercase tracking-wider text-neutral-900 hover:text-brand-600 transition-colors">All Posts →</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-0 border-t border-l border-neutral-200">
                @foreach($latestBlogs as $blog)
                    <a href="{{ route('blog.show', $blog->slug) }}" class="group border-r border-b border-neutral-200 p-6 md:p-8 hover:bg-neutral-900 transition-colors duration-300">
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
