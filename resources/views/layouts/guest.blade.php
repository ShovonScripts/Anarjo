<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Anarjo') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|Cormorant+Garamond:400,500,600,700&display=swap" rel="stylesheet" />
        <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet" />
        @if (setting('favicon'))
            <link rel="icon" type="image/png" href="{{ asset('storage/' . setting('favicon')) }}">
        @endif
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col">
            <x-public-header />
            <div class="h-24" aria-hidden="true"></div>

            <main class="flex-1 flex flex-col justify-center items-center py-16 px-4">
                <div class="w-full sm:max-w-md border-2 border-black bg-white p-8 md:p-10">
                    {{ $slot }}
                </div>
            </main>

            <footer class="bg-neutral-900 text-neutral-400">
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
    </body>
</html>
