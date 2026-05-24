@extends('layouts.frontend')

@section('title', 'Publication - ' . config('app.name'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-4">Publication</p>
    <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight leading-tight mb-8">
        Publish with<br>
        <span class="text-brand-600">Anarjo</span>
    </h1>
    <div class="text-lg text-neutral-600 leading-relaxed space-y-6 max-w-3xl">
        <p>We welcome manuscripts from aspiring and established authors. Our editorial team is committed to identifying and nurturing exceptional literary talent from Bangladesh and beyond.</p>
        <p>If you have a manuscript you'd like us to consider, we'd love to hear from you.</p>
    </div>
    <div class="mt-12">
        <livewire:manuscript-form />
    </div>
</div>
@endsection
