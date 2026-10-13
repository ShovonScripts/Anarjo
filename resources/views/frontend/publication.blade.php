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

<section class="bg-black">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-400 mb-4">Have a Question?</p>
        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white">We're Here to Help</h2>
        <p class="text-neutral-500 mt-4 max-w-xl mx-auto leading-relaxed">Whether you need guidance on submissions, have questions about the process, or just want to connect — reach out anytime.</p>
        <a href="{{ route('contact') }}" class="inline-block mt-8 bg-brand-600 hover:bg-brand-700 text-white px-8 py-3 text-sm font-semibold uppercase tracking-widest transition-colors">Contact Us</a>
    </div>
</section>
@endsection
