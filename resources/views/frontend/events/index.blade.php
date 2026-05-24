@extends('layouts.frontend')

@section('title', 'Events - ' . config('app.name'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="section-heading mb-12">Events & Media</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($events as $event)
            <div class="border border-neutral-200 bg-white hover:border-black transition-colors">
                @if($event->image)
                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-52 object-cover">
                @endif
                <div class="p-6">
                    @if($event->date)
                        <p class="text-xs font-semibold uppercase tracking-[0.15em] text-brand-600 mb-2">{{ $event->date->format('M d, Y') }}</p>
                    @endif
                    <h2 class="font-bold text-lg mb-2">{{ $event->title }}</h2>
                    @if($event->venue)
                        <p class="text-sm text-neutral-500 mb-3">{{ $event->venue }}</p>
                    @endif
                    <p class="text-sm text-neutral-600 leading-relaxed">{{ Str::limit($event->description, 120) }}</p>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-20 text-neutral-500">No events yet.</div>
        @endforelse
    </div>
    <div class="mt-12">{{ $events->links() }}</div>
</div>
@endsection
