@extends('layouts.frontend')

@section('title', 'About - ' . config('app.name'))

@section('content')
    {{-- Hero Section --}}
    <section class="relative bg-neutral-900 border-b border-neutral-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-28 md:py-36 text-center">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-400 mb-4">{{ setting('about_label', 'About Us') }}</p>
            <h1 class="text-2xl md:text-3xl font-light leading-relaxed text-white">
                {{ setting('about_hero_title', 'We believe in the power of words') }}
            </h1>
            @if (setting('about_subtitle'))
                <p class="text-neutral-400 mt-4 max-w-xl mx-auto leading-relaxed">{{ setting('about_subtitle') }}</p>
            @endif
        </div>
    </section>

    {{-- Image Slider --}}
    @php $aboutSlides = \App\Models\HeroSlide::where('page', 'about')->where('status', true)->orderBy('sort_order')->get(); @endphp
    @if($aboutSlides->isNotEmpty())
    <section class="border-b border-neutral-200 overflow-hidden">
        <div class="relative" x-data="{ current: 0 }" x-init="setInterval(() => { current = (current + 1) % {{ $aboutSlides->count() }} }, 5000)">
            @foreach($aboutSlides as $i => $slide)
            <div x-show="current === {{ $i }}" x-cloak class="w-full">
                @if($slide->link)
                <a href="{{ $slide->link }}" target="_blank" rel="noopener noreferrer">
                    <img src="{{ asset('storage/' . $slide->image) }}" class="w-full h-[300px] md:h-[450px] object-cover">
                </a>
                @else
                <img src="{{ asset('storage/' . $slide->image) }}" class="w-full h-[300px] md:h-[450px] object-cover">
                @endif
            </div>
            @endforeach
            @if($aboutSlides->count() > 1)
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-2 z-10">
                @foreach($aboutSlides as $i => $slide)
                <button @click="current = {{ $i }}" class="w-2.5 h-2.5 rounded-full transition-colors" :class="current === {{ $i }} ? 'bg-white' : 'bg-white/40'"></button>
                @endforeach
            </div>
            @endif
        </div>
    </section>
    @endif

    {{-- Content --}}
    @if (setting('about_content'))
    <section class="border-b border-neutral-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-lg text-neutral-600 leading-relaxed space-y-6 prose prose-lg max-w-none">
                {!! setting('about_content') !!}
            </div>
        </div>
    </section>
    @endif

    {{-- Mission & Vision --}}
    @if (setting('about_mission') || setting('about_vision'))
    <section class="border-b border-neutral-200 bg-neutral-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                @if (setting('about_mission'))
                <div class="border-2 border-black p-8 bg-white">
                    <div class="w-12 h-12 bg-black flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h2 class="text-xl font-extrabold tracking-tight mb-4">{{ setting('about_mission_heading', 'Our Mission') }}</h2>
                    <div class="text-neutral-600 leading-relaxed prose prose-sm max-w-none">{!! setting('about_mission') !!}</div>
                </div>
                @endif
                @if (setting('about_vision'))
                <div class="border-2 border-black p-8 bg-white">
                    <div class="w-12 h-12 bg-black flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
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
    <section class="border-b border-neutral-200 bg-neutral-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid grid-cols-3 gap-8">
                <div class="text-center">
                    <p class="text-4xl md:text-5xl font-extrabold tracking-tight">{{ $stats['books'] ?? 0 }}</p>
                    <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mt-1">Published Books</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl md:text-5xl font-extrabold tracking-tight">{{ $stats['authors'] ?? 0 }}</p>
                    <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mt-1">Authors</p>
                </div>
                <div class="text-center">
                    <p class="text-4xl md:text-5xl font-extrabold tracking-tight">{{ $stats['categories'] ?? 0 }}</p>
                    <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mt-1">Categories</p>
                </div>
            </div>
        </div>
    </section>
    @endif
@endsection
