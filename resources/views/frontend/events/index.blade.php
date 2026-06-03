@extends('layouts.frontend')

@section('title', 'Events - ' . config('app.name'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="section-heading mb-12">Events & Media</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($events as $event)
            <a href="{{ route('events.show', $event->slug) }}" class="group border transition-colors block {{ $event->is_major ? 'border-yellow-400 bg-yellow-50 hover:border-yellow-500' : 'border-neutral-200 bg-white hover:border-black' }}">
                @if($event->image)
                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-52 object-cover">
                @endif
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-2">
                        @if($event->date)
                            <p class="text-xs font-semibold uppercase tracking-[0.15em] {{ $event->is_major ? 'text-yellow-700' : 'text-brand-600' }}">{{ $event->date->format('M d, Y') }}</p>
                        @endif
                        @if($event->is_major)
                            <span class="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wider text-yellow-700">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                Major Event
                            </span>
                        @endif
                    </div>
                    <h2 class="font-bold text-lg mb-2 group-hover:text-brand-600 transition-colors">{{ $event->title }}</h2>
                    @if($event->venue)
                        <p class="text-sm text-neutral-500 mb-3">{{ $event->venue }}</p>
                    @endif
                    <p class="text-sm text-neutral-600 leading-relaxed">{{ Str::limit(strip_tags($event->description), 120) }}</p>
                </div>
            </a>
        @empty
            <div class="col-span-3 text-center py-20 text-neutral-500">No events yet.</div>
        @endforelse
    </div>
    <div class="mt-12">{{ $events->links() }}</div>
</div>
@endsection
