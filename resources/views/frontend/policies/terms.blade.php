@extends('layouts.frontend')

@section('title', 'Terms & Conditions - ' . config('app.name'))

@section('content')
<section class="border-b border-neutral-200 bg-neutral-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-28 md:py-36 text-center">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-400 mb-4">Legal</p>
        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white">Terms &amp; Conditions</h1>
    </div>
</section>

@if (setting('terms_conditions'))
<section class="border-b border-neutral-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="prose prose-lg max-w-none leading-relaxed">
            {!! setting('terms_conditions') !!}
        </div>
    </div>
</section>
@endif
@endsection
