<div x-data="{ mobileMenu: false }">
    {{-- Top Bar --}}
    <div class="bg-black text-white text-xs uppercase tracking-widest py-2">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <span>শূদ্রের বীনায় বাজুক রুদ্রের সুর</span>
                <span class="hidden sm:block">@if (setting('shipping_free_threshold')) Free Shipping on Orders Over {{ setting('currency_symbol', '৳') }}{{ number_format((int) setting('shipping_free_threshold')) }} @else Free Shipping @endif</span>
        </div>
    </div>

    {{-- Header --}}
    <header class="bg-white border-b border-neutral-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    @if (setting('header_logo'))
                        <img src="{{ asset('storage/' . setting('header_logo')) }}" alt="{{ setting('site_name', config('app.name')) }}" class="h-10">
                    @else
                        <span class="text-2xl font-extrabold tracking-tight text-black hover:text-brand-600 transition-colors">{{ setting('site_name', config('app.name', 'Anarjo')) }}</span>
                    @endif
                </a>
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'nav-link-active' : '' }}">Home</a>
                    <a href="{{ route('books.index') }}" class="nav-link {{ request()->routeIs('books.*') ? 'nav-link-active' : '' }}">Books</a>
                    <a href="{{ route('blog.index') }}" class="nav-link {{ request()->routeIs('blog.*') ? 'nav-link-active' : '' }}">Blog</a>
                    <a href="{{ route('publication') }}" class="nav-link {{ request()->routeIs('publication*') ? 'nav-link-active' : '' }}">Publication</a>
                    <a href="{{ route('events.index') }}" class="nav-link {{ request()->routeIs('events.*') ? 'nav-link-active' : '' }}">Events</a>
                    <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'nav-link-active' : '' }}">About</a>
                </nav>
                <div class="flex items-center gap-4">
                    <livewire:cart-badge />
                    @auth
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 text-sm font-medium text-neutral-700 hover:text-black transition-colors">
                                <span class="w-7 h-7 bg-neutral-200 flex items-center justify-center text-xs font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white border border-neutral-200 z-50">
                                <a href="{{ route('profile') }}" class="block px-4 py-3 text-sm text-neutral-700 hover:bg-neutral-50 border-b border-neutral-200">Profile</a>
                                <a href="{{ route('wishlist') }}" class="block px-4 py-3 text-sm text-neutral-700 hover:bg-neutral-50 border-b border-neutral-200">Wishlist</a>
                                <a href="{{ route('orders.index') }}" class="block px-4 py-3 text-sm text-neutral-700 hover:bg-neutral-50 border-b border-neutral-200">Orders</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-3 text-sm text-brand-600 hover:bg-neutral-50">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="nav-link">Login</a>
                        <a href="{{ route('register') }}" class="btn-primary btn-sm">Register</a>
                    @endauth
                    <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 hover:bg-neutral-100 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>
        </div>
        <div x-show="mobileMenu" class="md:hidden border-t border-neutral-200 bg-white">
            <div class="px-4 py-3 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 text-sm font-medium uppercase tracking-wider text-neutral-600 hover:text-black">Home</a>
                <a href="{{ route('books.index') }}" class="block px-3 py-2 text-sm font-medium uppercase tracking-wider text-neutral-600 hover:text-black">Books</a>
                <a href="{{ route('blog.index') }}" class="block px-3 py-2 text-sm font-medium uppercase tracking-wider text-neutral-600 hover:text-black">Blog</a>
                <a href="{{ route('events.index') }}" class="block px-3 py-2 text-sm font-medium uppercase tracking-wider text-neutral-600 hover:text-black">Events</a>
                <a href="{{ route('about') }}" class="block px-3 py-2 text-sm font-medium uppercase tracking-wider text-neutral-600 hover:text-black">About</a>
                @auth
                    <a href="{{ route('wishlist') }}" class="block px-3 py-2 text-sm font-medium uppercase tracking-wider text-neutral-600 hover:text-black">Wishlist</a>
                    <a href="{{ route('orders.index') }}" class="block px-3 py-2 text-sm font-medium uppercase tracking-wider text-neutral-600 hover:text-black">Orders</a>
                @endauth
            </div>
        </div>
    </header>
</div>
