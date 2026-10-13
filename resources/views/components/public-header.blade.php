<div x-data="{ mobileMenu: false }">
    <div class="fixed top-0 left-0 right-0 z-40">
        {{-- Top Bar --}}
        <div class="bg-neutral-900 text-white text-xs uppercase tracking-widest h-8 flex items-center overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center w-full whitespace-nowrap">
                <span>শূদ্রের বীণায় বাজুক রুদ্রের সুর</span>
                    <span class="hidden sm:block">@if (setting('shipping_free_threshold')) Free Shipping on Orders Over {{ setting('currency_symbol', '৳') }}{{ number_format((int) setting('shipping_free_threshold')) }} @else Free Shipping @endif</span>
            </div>
        </div>

        {{-- Header --}}
        <header class="bg-white/95 backdrop-blur-md border-b border-neutral-200/80 transition-shadow duration-300 shadow-[0_1px_3px_rgba(0,0,0,0.03)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        @if (setting('header_logo'))
                            <img src="{{ asset('storage/' . setting('header_logo')) }}" alt="{{ setting('site_name', config('app.name')) }}" class="h-10">
                        @else
                            <span class="text-2xl font-extrabold tracking-tight text-neutral-900 hover:text-brand-600 transition-colors">{{ setting('site_name', config('app.name', 'Anarjo')) }}</span>
                        @endif
                    </a>
                    <nav class="hidden md:flex items-center space-x-7 lg:space-x-8">
                        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'nav-link-active' : '' }}">Home</a>
                        <a href="{{ route('books.index') }}" class="nav-link {{ request()->routeIs('books.*') ? 'nav-link-active' : '' }}">Books</a>
                        <a href="{{ route('stationeries.index') }}" class="nav-link {{ request()->routeIs('stationeries.*') ? 'nav-link-active' : '' }}">Stationery</a>
                        <a href="{{ route('blog.index') }}" class="nav-link {{ request()->routeIs('blog.*') ? 'nav-link-active' : '' }}">Blog</a>
                        <a href="{{ route('publication') }}" class="nav-link {{ request()->routeIs('publication*') ? 'nav-link-active' : '' }}">Publication</a>
                        <a href="{{ route('events.index') }}" class="nav-link {{ request()->routeIs('events.*') ? 'nav-link-active' : '' }}">Events</a>
                        <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'nav-link-active' : '' }}">About</a>
                    </nav>
                    <div class="hidden md:flex items-center">
                        <form method="GET" action="{{ route('search') }}" class="relative group">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search..." class="w-36 lg:w-52 border border-neutral-300 bg-neutral-50/80 px-3 py-1.5 text-sm placeholder-neutral-400 focus:w-56 focus:border-neutral-900 focus:bg-white focus:ring-0 focus:outline-none transition-all duration-300">
                            <button type="submit" aria-label="Search" class="absolute right-2 top-1/2 -translate-y-1/2 text-neutral-400 group-hover:text-neutral-900 transition-colors">
                                <x-icon name="search" class="w-4 h-4" />
                            </button>
                        </form>
                    </div>
                    <div class="flex items-center gap-4">
                        <livewire:cart-badge />
                        @auth
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 text-sm font-medium text-neutral-700 hover:text-neutral-900 transition-colors">
                                    <span class="w-7 h-7 bg-neutral-200 flex items-center justify-center text-xs font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                                    <x-icon name="chevron-down" class="w-4 h-4" x-bind:class="{ 'rotate-180': open }" />
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
                            <div class="hidden md:flex items-center gap-4">
                                <a href="{{ route('login') }}" class="nav-link">Login</a>
                                <a href="{{ route('register') }}" class="btn-primary btn-sm">Register</a>
                            </div>
                        @endauth
                        <button @click="mobileMenu = !mobileMenu" class="md:hidden p-2 hover:bg-neutral-100 transition-colors" aria-label="Toggle navigation menu" :aria-expanded="mobileMenu">
                            <x-icon name="menu" class="w-6 h-6" />
                        </button>
                    </div>
                </div>
            </div>
        </header>
    </div>

    {{-- Mobile Slide-in Drawer --}}
    <div x-show="mobileMenu" x-cloak class="fixed inset-0 z-50 md:hidden" x-transition:enter="transition-all duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-all duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div @click="mobileMenu = false" class="absolute inset-0 bg-neutral-900/60 backdrop-blur-sm"></div>
        <div class="absolute inset-y-0 left-0 w-72 max-w-[80vw] bg-white border-r border-neutral-200 shadow-2xl overflow-y-auto"
             x-transition:enter="transition-all duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
             x-transition:leave="transition-all duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
             @click.away="mobileMenu = false">
            <div class="p-6 border-b border-neutral-200 flex items-center justify-between">
                @if (setting('header_logo'))
                    <img src="{{ asset('storage/' . setting('header_logo')) }}" alt="{{ setting('site_name', config('app.name')) }}" class="h-8">
                @else
                    <span class="text-lg font-extrabold tracking-tight">{{ setting('site_name', config('app.name', 'Anarjo')) }}</span>
                @endif
                <button @click="mobileMenu = false" class="p-2 hover:bg-neutral-100 transition-colors" aria-label="Close menu">
                    <x-icon name="close" class="w-5 h-5" />
                </button>
            </div>
            <div class="px-4 py-3 border-b border-neutral-200">
                <form method="GET" action="{{ route('search') }}" class="relative">
                    <input type="text" name="q" placeholder="Search books, authors, blogs..." class="w-full border border-neutral-300 bg-neutral-50 px-3 py-2 text-sm placeholder-neutral-400 focus:border-neutral-900 focus:ring-0 focus:outline-none">
                    <button type="submit" aria-label="Search" class="absolute right-2 top-1/2 -translate-y-1/2 text-neutral-400">
                        <x-icon name="search" class="w-4 h-4" />
                    </button>
                </form>
            </div>
            <div class="px-4 py-4 space-y-1">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-3 text-sm font-medium text-neutral-700 hover:text-neutral-900 hover:bg-neutral-50 rounded-lg transition-colors {{ request()->routeIs('home') ? 'bg-neutral-50 text-neutral-900 font-semibold' : '' }}">
                    <x-icon name="home" class="w-5 h-5 text-neutral-400" />
                    Home
                </a>
                <a href="{{ route('books.index') }}" class="flex items-center gap-3 px-3 py-3 text-sm font-medium text-neutral-700 hover:text-neutral-900 hover:bg-neutral-50 rounded-lg transition-colors {{ request()->routeIs('books.*') ? 'bg-neutral-50 text-neutral-900 font-semibold' : '' }}">
                    <x-icon name="book" class="w-5 h-5 text-neutral-400" />
                    Books
                </a>
                <a href="{{ route('stationeries.index') }}" class="flex items-center gap-3 px-3 py-3 text-sm font-medium text-neutral-700 hover:text-neutral-900 hover:bg-neutral-50 rounded-lg transition-colors {{ request()->routeIs('stationeries.*') ? 'bg-neutral-50 text-neutral-900 font-semibold' : '' }}">
                    <x-icon name="cog" class="w-5 h-5 text-neutral-400" />
                    Stationery
                </a>
                <a href="{{ route('blog.index') }}" class="flex items-center gap-3 px-3 py-3 text-sm font-medium text-neutral-700 hover:text-neutral-900 hover:bg-neutral-50 rounded-lg transition-colors {{ request()->routeIs('blog.*') ? 'bg-neutral-50 text-neutral-900 font-semibold' : '' }}">
                    <x-icon name="document" class="w-5 h-5 text-neutral-400" />
                    Blog
                </a>
                <a href="{{ route('publication') }}" class="flex items-center gap-3 px-3 py-3 text-sm font-medium text-neutral-700 hover:text-neutral-900 hover:bg-neutral-50 rounded-lg transition-colors {{ request()->routeIs('publication*') ? 'bg-neutral-50 text-neutral-900 font-semibold' : '' }}">
                    <x-icon name="book" class="w-5 h-5 text-neutral-400" />
                    Publication
                </a>
                <a href="{{ route('events.index') }}" class="flex items-center gap-3 px-3 py-3 text-sm font-medium text-neutral-700 hover:text-neutral-900 hover:bg-neutral-50 rounded-lg transition-colors {{ request()->routeIs('events.*') ? 'bg-neutral-50 text-neutral-900 font-semibold' : '' }}">
                    <x-icon name="calendar" class="w-5 h-5 text-neutral-400" />
                    Events
                </a>
                <a href="{{ route('about') }}" class="flex items-center gap-3 px-3 py-3 text-sm font-medium text-neutral-700 hover:text-neutral-900 hover:bg-neutral-50 rounded-lg transition-colors {{ request()->routeIs('about') ? 'bg-neutral-50 text-neutral-900 font-semibold' : '' }}">
                    <x-icon name="info" class="w-5 h-5 text-neutral-400" />
                    About
                </a>
                <hr class="my-3 border-neutral-200">
                @auth
                    <a href="{{ route('wishlist') }}" class="flex items-center gap-3 px-3 py-3 text-sm font-medium text-neutral-700 hover:text-neutral-900 hover:bg-neutral-50 rounded-lg transition-colors">
                        <x-icon name="heart" class="w-5 h-5 text-neutral-400" />
                        Wishlist
                    </a>
                    <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-3 py-3 text-sm font-medium text-neutral-700 hover:text-neutral-900 hover:bg-neutral-50 rounded-lg transition-colors">
                        <x-icon name="copy" class="w-5 h-5 text-neutral-400" />
                        Orders
                    </a>
                @endauth
                @guest
                    <hr class="my-3 border-neutral-200">
                    <a href="{{ route('login') }}" class="flex items-center gap-3 px-3 py-3 text-sm font-medium text-neutral-700 hover:text-neutral-900 hover:bg-neutral-50 rounded-lg transition-colors">
                        <x-icon name="logout" class="w-5 h-5 text-neutral-400" />
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="flex items-center gap-3 px-3 py-3 text-sm font-medium text-neutral-700 hover:text-neutral-900 hover:bg-neutral-50 rounded-lg transition-colors">
                        <x-icon name="user-add" class="w-5 h-5 text-neutral-400" />
                        Register
                    </a>
                @endguest
            </div>
        </div>
    </div>
</div>
