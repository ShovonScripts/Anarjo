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
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|Cormorant+Garamond:400,500,600,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet" />
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
    {{-- Top loading bar for page transitions --}}
    <div class="fixed top-0 left-0 right-0 z-[100] h-0.5 bg-brand-600 transition-opacity duration-200 opacity-0" wire:loading.class="opacity-100"></div>

    <div class="min-h-screen flex flex-col">
        <x-public-header />
        <div class="h-24" aria-hidden="true"></div>

        <main class="flex-1">
            @yield('content')
            {{ $slot ?? '' }}
        </main>

        {{-- Footer --}}
        <footer class="bg-neutral-900 text-neutral-400">
            {{-- Newsletter --}}
            <div class="border-b border-neutral-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                        <div>
                            <h3 class="text-white text-lg font-serif font-bold">Stay in the Loop</h3>
                            <p class="text-sm text-neutral-500 mt-1">Get notified about new releases and exclusive offers.</p>
                        </div>
                        <form method="POST" action="{{ route('newsletter.subscribe') }}" class="flex w-full md:w-auto gap-2">
                            @csrf
                            <input type="email" name="email" placeholder="Your email" required class="flex-1 md:w-64 px-4 py-2.5 bg-neutral-800 border border-neutral-700 text-sm text-white placeholder-neutral-500 focus:border-neutral-500 focus:ring-0 focus:outline-none transition-colors">
                            <button type="submit" class="px-5 py-2.5 bg-white text-neutral-900 text-sm font-semibold uppercase tracking-wider hover:bg-neutral-100 transition-colors shrink-0">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 md:gap-12">
                    <div class="sm:col-span-2 md:col-span-1">
                        @if (setting('footer_logo'))
                            <img src="{{ asset('storage/' . setting('footer_logo')) }}" alt="{{ setting('site_name', config('app.name')) }}" class="h-12 mb-4">
                        @else
                            <h3 class="text-white text-lg font-serif font-bold tracking-tight mb-4">{{ setting('site_name', config('app.name', 'Anarjo')) }}</h3>
                        @endif
                        <p class="text-sm leading-relaxed text-neutral-400">{{ setting('site_tagline', 'Bangladesh\'s leading publication house delivering knowledge since inception.') }}</p>
                    </div>
                    <div>
                        <h4 class="text-white text-xs font-semibold uppercase tracking-widest mb-5">Shop</h4>
                        <ul class="space-y-3">
                            <li><a href="{{ route('books.index') }}" class="text-sm text-neutral-400 hover:text-white transition-colors">Books</a></li>
                            <li><a href="{{ route('stationeries.index') }}" class="text-sm text-neutral-400 hover:text-white transition-colors">Stationery</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white text-xs font-semibold uppercase tracking-widest mb-5">Company</h4>
                        <ul class="space-y-3">
                            <li><a href="{{ route('about') }}" class="text-sm text-neutral-400 hover:text-white transition-colors">About Us</a></li>
                            <li><a href="{{ route('publication') }}" class="text-sm text-neutral-400 hover:text-white transition-colors">Publication</a></li>
                            <li><a href="{{ route('events.index') }}" class="text-sm text-neutral-400 hover:text-white transition-colors">Events</a></li>
                            <li><a href="{{ route('blog.index') }}" class="text-sm text-neutral-400 hover:text-white transition-colors">Blog</a></li>
                            <li><a href="{{ route('contact') }}" class="text-sm text-neutral-400 hover:text-white transition-colors">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white text-xs font-semibold uppercase tracking-widest mb-5">Support</h4>
                        <ul class="space-y-3">
                            <li><a href="{{ route('privacy-policy') }}" class="text-sm text-neutral-400 hover:text-white transition-colors">Privacy Policy</a></li>
                            <li><a href="{{ route('terms-conditions') }}" class="text-sm text-neutral-400 hover:text-white transition-colors">Terms &amp; Conditions</a></li>
                            <li><a href="{{ route('return-refund') }}" class="text-sm text-neutral-400 hover:text-white transition-colors">Return &amp; Refund</a></li>
                        </ul>
                        <div class="mt-6">
                            @if (setting('social_facebook') || setting('social_twitter') || setting('social_instagram'))
                                <div class="flex gap-3">
                                    @if (setting('social_facebook'))
                                        <a href="{{ setting('social_facebook') }}" target="_blank" aria-label="Facebook" class="text-neutral-500 hover:text-white transition-colors">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                        </a>
                                    @endif
                                    @if (setting('social_twitter'))
                                        <a href="{{ setting('social_twitter') }}" target="_blank" aria-label="Twitter" class="text-neutral-500 hover:text-white transition-colors">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                        </a>
                                    @endif
                                    @if (setting('social_instagram'))
                                        <a href="{{ setting('social_instagram') }}" target="_blank" aria-label="Instagram" class="text-neutral-500 hover:text-white transition-colors">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t border-neutral-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-sm text-neutral-500">
                    <span>&copy; {{ date('Y') }} {{ setting('site_name', config('app.name', 'Anarjo')) }}. All rights reserved.</span>
                    <div class="flex items-center gap-3 text-xs">
                        <span>{{ setting('site_tagline', 'Knowledge for all.') }}</span>
                        <span class="text-neutral-700">|</span>
                        <span>Crafted by <a href="https://facebook.com/shovon.5271" target="_blank" rel="noopener noreferrer" class="text-neutral-400 hover:text-brand-500 font-medium transition-colors">Shovon</a></span>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    {{-- Toast Notification --}}
    <div x-data="{ show: false, message: '', type: 'default' }"
         @notify.window="message = $event.detail.message; type = $event.detail.type || 'default'; show = true; setTimeout(() => show = false, 4000)"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed bottom-6 right-6 z-50 max-w-sm">
        <div class="bg-black text-white px-5 py-3.5 shadow-2xl flex items-center gap-3">
            <template x-if="type === 'cart-added'">
                <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </template>
            <template x-if="type !== 'cart-added'">
                <svg class="w-5 h-5 text-neutral-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </template>
            <span x-text="message" class="text-sm font-medium"></span>
            <template x-if="type === 'cart-added'">
                <a href="/cart" class="text-xs font-bold uppercase tracking-wider text-white/80 hover:text-white transition-colors whitespace-nowrap border-l border-white/20 pl-3 ml-1">View Cart</a>
            </template>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
