<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Anarjo Publications'))</title>
    @hasSection('seo')
        @yield('seo')
    @else
        <meta name="description" content="@yield('meta_description', config('app.name', 'Anarjo Publications'))">
    @endif
    @stack('meta')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @if (setting('favicon'))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . setting('favicon')) }}">
    @endif
    @if (setting('seo_google_analytics_id'))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ setting('seo_google_analytics_id') }}"></script>
        <script>window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', '{{ setting('seo_google_analytics_id') }}');</script>
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <div class="min-h-screen flex flex-col">
        <x-public-header />

        <main class="flex-1">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="bg-black text-neutral-400">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                    <div class="md:col-span-1">
                        @if (setting('footer_logo'))
                            <img src="{{ asset('storage/' . setting('footer_logo')) }}" alt="{{ setting('site_name', config('app.name')) }}" class="h-12 mb-4">
                        @else
                            <h3 class="text-white text-lg font-bold tracking-tight mb-4">{{ setting('site_name', config('app.name', 'Anarjo')) }}</h3>
                        @endif
                        <p class="text-sm leading-relaxed">{{ setting('site_tagline', 'Bangladesh\'s leading publication house delivering knowledge since inception.') }}</p>
                    </div>
                    <div>
                        <h4 class="text-white text-sm font-semibold uppercase tracking-widest mb-4">Quick Links</h4>
                        <ul class="space-y-3">
                            <li><a href="{{ route('books.index') }}" class="text-sm hover:text-white transition-colors">Books</a></li>
                            <li><a href="{{ route('about') }}" class="text-sm hover:text-white transition-colors">About Us</a></li>
                            <li><a href="{{ route('publication') }}" class="text-sm hover:text-white transition-colors">Publication</a></li>
                            <li><a href="{{ route('events.index') }}" class="text-sm hover:text-white transition-colors">Events</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white text-sm font-semibold uppercase tracking-widest mb-4">Categories</h4>
                        <ul class="space-y-3">
                            <li><a href="#" class="text-sm hover:text-white transition-colors">গল্প</a></li>
                            <li><a href="#" class="text-sm hover:text-white transition-colors">উপন্যাস</a></li>
                            <li><a href="#" class="text-sm hover:text-white transition-colors">ইসলামিক</a></li>
                            <li><a href="#" class="text-sm hover:text-white transition-colors">শিশু</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white text-sm font-semibold uppercase tracking-widest mb-4">Contact</h4>
                        <ul class="space-y-3 text-sm">
                            @if (setting('contact_address'))
                                <li>{{ setting('contact_address') }}</li>
                            @else
                                <li>Dhaka, Bangladesh</li>
                            @endif
                            @if (setting('contact_phone'))
                                <li><a href="tel:{{ setting('contact_phone') }}" class="hover:text-white transition-colors">{{ setting('contact_phone') }}</a></li>
                            @endif
                            @if (setting('contact_email'))
                                <li><a href="mailto:{{ setting('contact_email') }}" class="hover:text-white transition-colors">{{ setting('contact_email') }}</a></li>
                            @else
                                <li><a href="mailto:info@anarjo.com" class="hover:text-white transition-colors">info@anarjo.com</a></li>
                            @endif
                        </ul>
                        @if (setting('social_facebook') || setting('social_twitter') || setting('social_instagram'))
                            <div class="flex gap-3 mt-4">
                                @if (setting('social_facebook'))
                                    <a href="{{ setting('social_facebook') }}" target="_blank" class="text-neutral-500 hover:text-white transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    </a>
                                @endif
                                @if (setting('social_twitter'))
                                    <a href="{{ setting('social_twitter') }}" target="_blank" class="text-neutral-500 hover:text-white transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                    </a>
                                @endif
                                @if (setting('social_instagram'))
                                    <a href="{{ setting('social_instagram') }}" target="_blank" class="text-neutral-500 hover:text-white transition-colors">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="border-t border-neutral-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center text-sm">
                    &copy; {{ date('Y') }} {{ setting('site_name', config('app.name', 'Anarjo')) }}. All rights reserved.
                </div>
            </div>
        </footer>
    </div>

    {{-- Toast Notification --}}
    <div x-data="{ show: false, message: '' }"
         @notify.window="message = $event.detail.message; show = true; setTimeout(() => show = false, 3000)"
         x-show="show"
         x-transition
         class="fixed bottom-6 right-6 bg-black text-white px-6 py-3 z-50 border border-white">
        <span x-text="message" class="text-sm font-medium uppercase tracking-wider"></span>
    </div>
    @stack('scripts')
</body>
</html>
