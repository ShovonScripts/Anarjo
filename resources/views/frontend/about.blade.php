@extends('layouts.frontend')

@section('title', 'About - ' . config('app.name'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-4">About Us</p>
    <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight leading-tight mb-8">
        We believe in the<br>
        <span class="text-brand-600">power of words</span>
    </h1>
    <div class="text-lg text-neutral-600 leading-relaxed space-y-6 max-w-3xl">
        <p>Anarjo Publications is শূদ্রের বীনায় বাজুক রুদ্রের সুর, dedicated to bringing the finest literature to readers across the nation. Our mission is to nurture Bangladeshi voices and share stories that matter.</p>
        <p>From thought-provoking fiction to academic excellence, from Islamic scholarship to children's imagination, we publish books that educate, inspire, and entertain.</p>
    </div>
</div>
@endsection
