@extends('layouts.frontend')

@section('title', $event->title . ' - Events - ' . config('app.name'))
@section('meta_description', Str::limit($event->description, 160))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-neutral-500 hover:text-black transition-colors mb-8">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Events
    </a>

    @if($event->image)
        <div class="mb-10">
            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-72 md:h-96 object-cover border border-neutral-200">
        </div>
    @endif

    <div class="flex flex-wrap items-center gap-6 text-xs uppercase tracking-widest text-neutral-500 mb-6">
        @if($event->date)
            <time datetime="{{ $event->date->toIso8601String() }}" class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ $event->date->format('F d, Y') }}
            </time>
        @endif
        @if($event->venue)
            <span class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                {{ $event->venue }}
            </span>
        @endif
    </div>

    <div class="flex items-center gap-4 mb-8">
        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight leading-tight">{{ $event->title }}</h1>
        @if($event->is_major)
            <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider bg-yellow-100 text-yellow-800 px-3 py-1.5 border border-yellow-300">
                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                Major Event
            </span>
        @endif
    </div>

    @if($event->description)
        <div class="prose prose-neutral max-w-none leading-relaxed event-content">
            {!! $event->description !!}
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.event-content h1 { font-size: 2rem; font-weight: 800; margin-top: 2rem; margin-bottom: 1rem; }
.event-content h2 { font-size: 1.5rem; font-weight: 700; margin-top: 1.75rem; margin-bottom: 0.75rem; }
.event-content h3 { font-size: 1.25rem; font-weight: 600; margin-top: 1.5rem; margin-bottom: 0.5rem; }
.event-content p { margin-bottom: 1.25rem; line-height: 1.75; }
.event-content ul, .event-content ol { margin-bottom: 1.25rem; padding-left: 1.5rem; }
.event-content li { margin-bottom: 0.5rem; }
.event-content blockquote { border-left: 4px solid #dc2626; padding-left: 1rem; margin: 1.5rem 0; color: #525252; font-style: italic; }
.event-content img { max-width: 100%; height: auto; margin: 1.5rem 0; border: 1px solid #e5e5e5; }
.event-content a { color: #dc2626; text-decoration: underline; }
.event-content a:hover { color: #b91c1c; }
.event-content pre { background: #f5f5f5; padding: 1rem; overflow-x: auto; border: 1px solid #e5e5e5; margin: 1.5rem 0; }
.event-content code { font-size: 0.875rem; }
.event-content table { width: 100%; border-collapse: collapse; margin: 1.5rem 0; }
.event-content th, .event-content td { border: 1px solid #e5e5e5; padding: 0.75rem; text-align: left; }
.event-content th { background: #f5f5f5; font-weight: 600; }
</style>
@endpush
