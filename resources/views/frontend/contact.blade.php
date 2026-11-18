@extends('layouts.frontend')

@section('title', 'Contact - ' . config('app.name'))

@section('content')
<section class="border-b border-neutral-200 bg-neutral-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-28 md:py-36 text-center">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-400 mb-4">{{ setting('contact_label', 'Get in Touch') }}</p>
        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white">{{ setting('contact_hero_title', 'Contact Us') }}</h1>
        <p class="text-neutral-500 mt-4 max-w-xl mx-auto leading-relaxed">{{ setting('contact_subtitle', 'Have a question, feedback, or inquiry? We\'d love to hear from you.') }}</p>
    </div>
</section>

<section class="border-b border-neutral-200">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
            {{-- Contact Info --}}
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight mb-8">Get in Touch</h2>
                <div class="space-y-6">
                    @if (setting('contact_address'))
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-black flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-widest mb-1">Address</p>
                            <p class="text-sm text-neutral-600 leading-relaxed">{{ setting('contact_address') }}</p>
                        </div>
                    </div>
                    @endif
                    @if (setting('contact_phone'))
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-black flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-widest mb-1">Phone</p>
                            <p class="text-sm text-neutral-600">{{ setting('contact_phone') }}</p>
                        </div>
                    </div>
                    @endif
                    @if (setting('contact_email'))
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-black flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold uppercase tracking-widest mb-1">Email</p>
                            <p class="text-sm text-neutral-600">{{ setting('contact_email') }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Social Links --}}
                @if (setting('social_facebook') || setting('social_twitter') || setting('social_instagram') || setting('social_linkedin') || setting('social_youtube'))
                <div class="mt-10 pt-10 border-t border-neutral-200">
                    <p class="text-sm font-semibold uppercase tracking-widest mb-4">Follow Us</p>
                    <div class="flex items-center gap-3">
                        @if (setting('social_facebook'))
                            <a href="{{ setting('social_facebook') }}" target="_blank" class="w-10 h-10 border-2 border-black flex items-center justify-center hover:bg-black hover:text-white transition-colors text-sm font-bold">f</a>
                        @endif
                        @if (setting('social_twitter'))
                            <a href="{{ setting('social_twitter') }}" target="_blank" class="w-10 h-10 border-2 border-black flex items-center justify-center hover:bg-black hover:text-white transition-colors text-sm font-bold">X</a>
                        @endif
                        @if (setting('social_instagram'))
                            <a href="{{ setting('social_instagram') }}" target="_blank" class="w-10 h-10 border-2 border-black flex items-center justify-center hover:bg-black hover:text-white transition-colors text-sm font-bold">IG</a>
                        @endif
                        @if (setting('social_linkedin'))
                            <a href="{{ setting('social_linkedin') }}" target="_blank" class="w-10 h-10 border-2 border-black flex items-center justify-center hover:bg-black hover:text-white transition-colors text-sm font-bold">in</a>
                        @endif
                        @if (setting('social_youtube'))
                            <a href="{{ setting('social_youtube') }}" target="_blank" class="w-10 h-10 border-2 border-black flex items-center justify-center hover:bg-black hover:text-white transition-colors text-sm font-bold">YT</a>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            {{-- Contact Form --}}
            <div>
                <h2 class="text-2xl font-extrabold tracking-tight mb-8">Send a Message</h2>
                @if (session('success'))
                    <div class="border-2 border-black bg-neutral-50 px-6 py-4 mb-6 text-sm font-medium">{{ session('success') }}</div>
                @endif
                <form method="POST" action="{{ route('contact.store') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Name <span class="text-brand-600">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="input-field" placeholder="Your name" required>
                        @error('name') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Email <span class="text-brand-600">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" class="input-field" placeholder="your@email.com" required>
                        @error('email') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Subject</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" class="input-field" placeholder="How can we help?">
                        @error('subject') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Message <span class="text-brand-600">*</span></label>
                        <textarea name="message" rows="6" class="input-field" placeholder="Write your message..." required>{{ old('message') }}</textarea>
                        @error('message') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" class="btn-primary">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

@php $faqs = json_decode(setting('contact_faqs', '[]'), true); @endphp
@if(!empty($faqs))
<section class="border-b border-neutral-200">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center mb-12">
            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-600 mb-4">Have Questions?</p>
            <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">Frequently Asked Questions</h2>
        </div>
        <div class="space-y-4" x-data="{ openIndex: null }">
            @foreach($faqs as $index => $faq)
                <div class="border border-neutral-200">
                    <button @click="openIndex = openIndex === {{ $index }} ? null : {{ $index }}" class="flex items-center justify-between w-full px-6 py-4 text-left text-sm font-semibold hover:bg-neutral-50 transition-colors">
                        <span>{{ $faq['question'] }}</span>
                        <svg class="w-4 h-4 shrink-0 transition-transform duration-200" :class="{ 'rotate-180': openIndex === {{ $index }} }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="openIndex === {{ $index }}" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="px-6 py-4 border-t border-neutral-200">
                        <p class="text-sm text-neutral-600 leading-relaxed">{{ $faq['answer'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
