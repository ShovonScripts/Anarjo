@extends('layouts.frontend')

@section('title', 'About - ' . config('app.name'))

@section('content')
    {{-- Hero Section --}}
    <section class="relative bg-neutral-900 border-b border-neutral-200 overflow-hidden">
        <div class="absolute -top-24 -right-24 w-80 h-80 bg-brand-600/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -left-24 w-80 h-80 bg-brand-600/10 rounded-full blur-3xl"></div>
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-28 md:py-36 text-center">
            <div class="flex items-center justify-center gap-3 mb-5">
                <span class="w-10 h-px bg-brand-400/60"></span>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-400">{{ setting('about_label', 'About Us') }}</p>
                <span class="w-10 h-px bg-brand-400/60"></span>
            </div>
            <h1 class="font-serif text-4xl sm:text-5xl md:text-6xl font-bold tracking-tight leading-tight text-white">
                {{ setting('about_hero_title', 'We believe in the power of words') }}
            </h1>
            @if (setting('about_subtitle'))
                <p class="text-neutral-400 mt-6 max-w-2xl mx-auto leading-relaxed text-base md:text-lg">{{ setting('about_subtitle') }}</p>
            @endif
            <div class="flex flex-wrap items-center justify-center gap-4 mt-10">
                <a href="{{ route('books.index') }}" class="btn-primary bg-white text-neutral-900 hover:bg-white/90">Explore Books</a>
                <a href="{{ route('contact') }}" class="btn-outline border-white text-white hover:bg-white hover:text-neutral-900">Contact Us</a>
            </div>
        </div>
    </section>

    {{-- Image Slider --}}
    @if($aboutSlides->isNotEmpty())
    <section class="border-b border-neutral-200 overflow-hidden reveal">
        <div class="relative bg-neutral-900" x-data="{ current: 0 }" x-init="setInterval(() => { current = (current + 1) % {{ $aboutSlides->count() }} }, 5000)">
            @foreach($aboutSlides as $i => $slide)
            <div x-show="current === {{ $i }}" x-cloak x-transition:enter="transition-opacity duration-700" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="w-full">
                @if($slide->link)
                <a href="{{ $slide->link }}" target="_blank" rel="noopener noreferrer">
                    <img src="{{ asset('storage/' . $slide->image) }}" alt="{{ $slide->title ?? 'About ' . setting('site_name', config('app.name')) }}" class="w-full h-[300px] md:h-[450px] object-cover opacity-90" loading="lazy">
                </a>
                @else
                <img src="{{ asset('storage/' . $slide->image) }}" alt="{{ $slide->title ?? 'About ' . setting('site_name', config('app.name')) }}" class="w-full h-[300px] md:h-[450px] object-cover opacity-90" loading="lazy">
                @endif
            </div>
            @endforeach
            @if($aboutSlides->count() > 1)
            <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-neutral-900/60 to-transparent pointer-events-none"></div>
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2 z-10">
                @foreach($aboutSlides as $i => $slide)
                <button @click="current = {{ $i }}" :aria-label="'Slide {{ $i + 1 }}'" class="w-2.5 h-2.5 rounded-full transition-all duration-300" :class="current === {{ $i }} ? 'bg-brand-400 w-6' : 'bg-white/40 hover:bg-white/70'"></button>
                @endforeach
            </div>
            @endif
        </div>
    </section>
    @endif

    {{-- Story Content --}}
    @if (setting('about_content'))
    <section class="border-b border-neutral-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28 reveal">
            <div class="text-center mb-12">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-3">Our Story</p>
                <div class="flex items-center justify-center gap-3">
                    <span class="w-12 h-px bg-neutral-900"></span>
                    <span class="w-1.5 h-1.5 bg-brand-600 rounded-full"></span>
                    <span class="w-12 h-px bg-neutral-900"></span>
                </div>
            </div>
            <div class="text-lg text-neutral-600 leading-relaxed space-y-6 prose prose-lg max-w-none">
                {!! setting('about_content') !!}
            </div>
        </div>
    </section>
    @endif

    {{-- Mission & Vision --}}
    @if (setting('about_mission') || setting('about_vision'))
    <section class="border-b border-neutral-200 bg-neutral-100/50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
            <div class="text-center mb-12 md:mb-16 reveal">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-3">What Drives Us</p>
                <h2 class="section-heading">Mission &amp; Vision</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-10">
                @if (setting('about_mission'))
                <div class="border-2 border-neutral-900 bg-white p-8 md:p-10 group hover:-translate-y-1 hover:shadow-[0_20px_40px_-20px_rgba(0,0,0,0.2)] transition-all duration-300 reveal">
                    <div class="w-14 h-14 bg-brand-600 flex items-center justify-center mb-6 group-hover:bg-neutral-900 transition-colors duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h2 class="text-xl font-extrabold tracking-tight mb-4">{{ setting('about_mission_heading', 'Our Mission') }}</h2>
                    <div class="text-neutral-600 leading-relaxed prose prose-sm max-w-none">{!! setting('about_mission') !!}</div>
                </div>
                @endif
                @if (setting('about_vision'))
                <div class="border-2 border-neutral-900 bg-white p-8 md:p-10 group hover:-translate-y-1 hover:shadow-[0_20px_40px_-20px_rgba(0,0,0,0.2)] transition-all duration-300 reveal">
                    <div class="w-14 h-14 bg-brand-600 flex items-center justify-center mb-6 group-hover:bg-neutral-900 transition-colors duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <h2 class="text-xl font-extrabold tracking-tight mb-4">{{ setting('about_vision_heading', 'Our Vision') }}</h2>
                    <div class="text-neutral-600 leading-relaxed prose prose-sm max-w-none">{!! setting('about_vision') !!}</div>
                </div>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- Stats --}}
    @if (($stats['books'] ?? 0) > 0 || ($stats['authors'] ?? 0) > 0 || ($stats['categories'] ?? 0) > 0)
    <section class="border-b border-neutral-200 bg-neutral-900 relative overflow-hidden">
        <div class="absolute -top-20 left-1/2 -translate-x-1/2 w-96 h-96 bg-brand-600/10 rounded-full blur-3xl"></div>
        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-24">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-10 md:gap-8">
                <div class="text-center reveal">
                    <p class="text-5xl md:text-6xl font-extrabold tracking-tight text-white">{{ $stats['books'] ?? 0 }}</p>
                    <p class="text-xs font-semibold uppercase tracking-widest text-brand-400 mt-2">Published Books</p>
                </div>
                <div class="text-center reveal">
                    <p class="text-5xl md:text-6xl font-extrabold tracking-tight text-white">{{ $stats['authors'] ?? 0 }}</p>
                    <p class="text-xs font-semibold uppercase tracking-widest text-brand-400 mt-2">Authors</p>
                </div>
                <div class="text-center reveal">
                    <p class="text-5xl md:text-6xl font-extrabold tracking-tight text-white">{{ $stats['categories'] ?? 0 }}</p>
                    <p class="text-xs font-semibold uppercase tracking-widest text-brand-400 mt-2">Categories</p>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- CTA --}}
    <section class="reveal">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-24 text-center">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-3">Let's Connect</p>
            <h2 class="section-heading">Join Our Journey</h2>
            <p class="text-neutral-500 mt-4 max-w-xl mx-auto leading-relaxed">Discover books, explore our catalogue, or get in touch — we'd love to hear from you.</p>
            <div class="flex flex-wrap items-center justify-center gap-4 mt-8">
                <a href="{{ route('books.index') }}" class="btn-primary">Browse Books</a>
                <a href="{{ route('contact') }}" class="btn-outline">Contact Us</a>
            </div>
        </div>
    </section>
@endsection
