<div x-data="{ mobileMenu: false, dropdowns: { books: false, stationery: false, blog: false, more: false } }">
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

                    {{-- Desktop Navigation with Dropdowns --}}
                    <nav class="hidden md:flex items-center space-x-1" x-data="dropdownNavigation()">
                        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'nav-link-active' : '' }} px-3 py-2 rounded-md">Home</a>

                        {{-- Books Dropdown --}}
                        <div class="relative" @mouseenter="openDropdown('books')" @mouseleave="closeDropdown('books')">
                            <button
                                type="button"
                                class="nav-link px-3 py-2 rounded-md flex items-center gap-1 {{ request()->routeIs('books.*') || request()->routeIs('author.*') || request()->routeIs('category.*') ? 'nav-link-active' : '' }}"
                                aria-haspopup="true"
                                aria-expanded="false"
                                x-ref="booksBtn"
                            >
                                Books
                                <x-icon name="chevron-down" class="w-4 h-4" x-bind:class="{ 'rotate-180': dropdowns.books }" />
                            </button>

                            <div
                                x-show="dropdowns.books"
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 transform -translate-y-1"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 transform translate-y-0"
                                x-transition:leave-end="opacity-0 transform -translate-y-1"
                                class="absolute left-0 top-full mt-2 w-64 bg-white border border-neutral-200 rounded-lg shadow-lg z-50 py-2"
                                @mouseenter="openDropdown('books')"
                                @mouseleave="closeDropdown('books')"
                                style="display: none;"
                            >
                                <a href="{{ route('books.index') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900 {{ request()->routeIs('books.index') ? 'bg-neutral-50 text-neutral-900 font-medium' : '' }}">All Books</a>
                                <a href="{{ route('books.index') }}?sort=newest" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">New Arrivals</a>
                                <a href="{{ route('books.index') }}?sort=most-visited" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">Most Popular</a>
                                <hr class="my-2 border-neutral-200">
                                <a href="{{ route('books.index') }}?sort=low-high" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">Price: Low to High</a>
                                <a href="{{ route('books.index') }}?sort=high-low" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">Price: High to Low</a>
                                <hr class="my-2 border-neutral-200">
                                <a href="{{ route('books.index') }}?category=probandho-o-gobeshona" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">প্রবন্ধ ও গবেষণা</a>
                                <a href="{{ route('books.index') }}?category=golpo-uponnas-bhraman" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">গল্প/উপন্যাস/ভ্রমণ</a>
                                <a href="{{ route('books.index') }}?category=natok" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">নাটক</a>
                                <a href="{{ route('books.index') }}?category=kobita" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">কবিতা</a>
                                <a href="{{ route('books.index') }}?category=anubad" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">অনুবাদ</a>
                                <a href="{{ route('books.index') }}?category=sastho" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">স্বাস্থ্য</a>
                                <hr class="my-2 border-neutral-200">
                                <a href="{{ route('books.index') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">All Categories</a>
                                <a href="{{ route('books.index') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">All Authors</a>
                                <a href="{{ route('book-fair.index') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">Book Fair Editions</a>
                            </div>
                        </div>

                        {{-- Stationery Dropdown --}}
                        <div class="relative" @mouseenter="openDropdown('stationery')" @mouseleave="closeDropdown('stationery')">
                            <button
                                type="button"
                                class="nav-link px-3 py-2 rounded-md flex items-center gap-1 {{ request()->routeIs('stationeries.*') ? 'nav-link-active' : '' }}"
                                aria-haspopup="true"
                                aria-expanded="false"
                                x-ref="stationeryBtn"
                            >
                                Stationery
                                <x-icon name="chevron-down" class="w-4 h-4" x-bind:class="{ 'rotate-180': dropdowns.stationery }" />
                            </button>

                            <div
                                x-show="dropdowns.stationery"
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 transform -translate-y-1"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 transform translate-y-0"
                                x-transition:leave-end="opacity-0 transform -translate-y-1"
                                class="absolute left-0 top-full mt-2 w-56 bg-white border border-neutral-200 rounded-lg shadow-lg z-50 py-2"
                                @mouseenter="openDropdown('stationery')"
                                @mouseleave="closeDropdown('stationery')"
                                style="display: none;"
                            >
                                <a href="{{ route('stationeries.index') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900 {{ request()->routeIs('stationeries.index') ? 'bg-neutral-50 text-neutral-900 font-medium' : '' }}">All Stationery</a>
                                <a href="{{ route('stationeries.index') }}?sort=newest" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">New Arrivals</a>
                                <hr class="my-2 border-neutral-200">
                                <a href="{{ route('stationeries.index') }}?sort=low-high" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">Price: Low to High</a>
                                <a href="{{ route('stationeries.index') }}?sort=high-low" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">Price: High to Low</a>
                                <hr class="my-2 border-neutral-200">
                                <a href="{{ route('stationeries.brand', 'matador') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">Matador</a>
                                <a href="{{ route('stationeries.brand', 'mango') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">Mango</a>
                                <a href="{{ route('stationeries.brand', 'deli') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">Deli</a>
                                <a href="{{ route('stationeries.brand', 'signature') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">Signature</a>
                                <hr class="my-2 border-neutral-200">
                                <a href="{{ route('stationeries.index') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">All Brands</a>
                            </div>
                        </div>

                        {{-- Blog Dropdown --}}
                        <div class="relative" @mouseenter="openDropdown('blog')" @mouseleave="closeDropdown('blog')">
                            <button
                                type="button"
                                class="nav-link px-3 py-2 rounded-md flex items-center gap-1 {{ request()->routeIs('blog.*') ? 'nav-link-active' : '' }}"
                                aria-haspopup="true"
                                aria-expanded="false"
                                x-ref="blogBtn"
                            >
                                Blog
                                <x-icon name="chevron-down" class="w-4 h-4" x-bind:class="{ 'rotate-180': dropdowns.blog }" />
                            </button>

                            <div
                                x-show="dropdowns.blog"
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 transform -translate-y-1"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 transform translate-y-0"
                                x-transition:leave-end="opacity-0 transform -translate-y-1"
                                class="absolute left-0 top-full mt-2 w-56 bg-white border border-neutral-200 rounded-lg shadow-lg z-50 py-2"
                                @mouseenter="openDropdown('blog')"
                                @mouseleave="closeDropdown('blog')"
                                style="display: none;"
                            >
                                <a href="{{ route('blog.index') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900 {{ request()->routeIs('blog.index') ? 'bg-neutral-50 text-neutral-900 font-medium' : '' }}">All Posts</a>
                                <hr class="my-2 border-neutral-200">
                                <a href="{{ route('blog.index') }}?category=book-reviews" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">Book Reviews</a>
                                <a href="{{ route('blog.index') }}?category=author-interviews" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">Author Interviews</a>
                                <a href="{{ route('blog.index') }}?category=literary-news" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">Literary News</a>
                                <a href="{{ route('blog.index') }}?category=reading-tips" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">Reading Tips</a>
                                <hr class="my-2 border-neutral-200">
                                <a href="{{ route('blog.index') }}?category=all" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">All Categories</a>
                                <a href="{{ route('blog.index') }}?tag=all" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">All Tags</a>
                            </div>
                        </div>

                        {{-- More Dropdown --}}
                        <div class="relative" @mouseenter="openDropdown('more')" @mouseleave="closeDropdown('more')">
                            <button
                                type="button"
                                class="nav-link px-3 py-2 rounded-md flex items-center gap-1 {{ request()->routeIs('events.*') || request()->routeIs('publication*') || request()->routeIs('about') || request()->routeIs('book-fair.*') ? 'nav-link-active' : '' }}"
                                aria-haspopup="true"
                                aria-expanded="false"
                                x-ref="moreBtn"
                            >
                                More
                                <x-icon name="chevron-down" class="w-4 h-4" x-bind:class="{ 'rotate-180': dropdowns.more }" />
                            </button>

                            <div
                                x-show="dropdowns.more"
                                x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 transform -translate-y-1"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                x-transition:leave="transition ease-in duration-100"
                                x-transition:leave-start="opacity-100 transform translate-y-0"
                                x-transition:leave-end="opacity-0 transform -translate-y-1"
                                class="absolute right-0 top-full mt-2 w-64 bg-white border border-neutral-200 rounded-lg shadow-lg z-50 py-2"
                                @mouseenter="openDropdown('more')"
                                @mouseleave="closeDropdown('more')"
                                style="display: none;"
                            >
                                <a href="{{ route('events.index') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900 {{ request()->routeIs('events.*') ? 'bg-neutral-50 text-neutral-900 font-medium' : '' }}">Events</a>
                                <a href="{{ route('book-fair.index') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900 {{ request()->routeIs('book-fair.*') ? 'bg-neutral-50 text-neutral-900 font-medium' : '' }}">Book Fair</a>
                                <hr class="my-2 border-neutral-200">
                                <a href="{{ route('publication') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900 {{ request()->routeIs('publication*') ? 'bg-neutral-50 text-neutral-900 font-medium' : '' }}">Submit Manuscript</a>
                                <hr class="my-2 border-neutral-200">
                                <a href="{{ route('about') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900 {{ request()->routeIs('about') ? 'bg-neutral-50 text-neutral-900 font-medium' : '' }}">About Us</a>
                                <a href="{{ route('contact') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900 {{ request()->routeIs('contact') ? 'bg-neutral-50 text-neutral-900 font-medium' : '' }}">Contact</a>
                                <hr class="my-2 border-neutral-200">
                                <a href="{{ route('privacy-policy') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">Privacy Policy</a>
                                <a href="{{ route('terms-conditions') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">Terms & Conditions</a>
                                <a href="{{ route('return-refund') }}" class="block px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 hover:text-neutral-900">Return & Refund</a>
                            </div>
                        </div>
                    </nav>

                    {{-- Search & Actions --}}
                    <div class="hidden md:flex items-center gap-4">
                        <form method="GET" action="{{ route('search') }}" class="relative group">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search..." class="w-36 lg:w-52 border border-neutral-300 bg-neutral-50/80 px-3 py-1.5 text-sm placeholder-neutral-400 focus:w-56 focus:border-neutral-900 focus:bg-white focus:ring-0 focus:outline-none transition-all duration-300">
                            <button type="submit" aria-label="Search" class="absolute right-2 top-1/2 -translate-y-1/2 text-neutral-400 group-hover:text-neutral-900 transition-colors">
                                <x-icon name="search" class="w-4 h-4" />
                            </button>
                        </form>

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
            <nav class="px-4 py-4 space-y-1">
                {{-- Home --}}
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-3 text-sm font-medium text-neutral-700 hover:text-neutral-900 hover:bg-neutral-50 rounded-lg transition-colors {{ request()->routeIs('home') ? 'bg-neutral-50 text-neutral-900 font-semibold' : '' }}">
                    <x-icon name="home" class="w-5 h-5 text-neutral-400" />
                    Home
                </a>

                {{-- Books Dropdown (Mobile) --}}
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-3 text-sm font-medium text-neutral-700 hover:text-neutral-900 hover:bg-neutral-50 rounded-lg transition-colors">
                        <span class="flex items-center gap-3">
                            <x-icon name="book" class="w-5 h-5 text-neutral-400" />
                            Books
                        </span>
                        <x-icon name="chevron-down" class="w-5 h-5 text-neutral-400" x-bind:class="{ 'rotate-180': open }" />
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="pl-8 space-y-1 border-l border-neutral-200 ml-2 pt-2">
                        <a href="{{ route('books.index') }}" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">All Books</a>
                        <a href="{{ route('books.index') }}?sort=newest" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">New Arrivals</a>
                        <a href="{{ route('books.index') }}?sort=most-visited" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">Most Popular</a>
                        <hr class="my-2 border-neutral-200">
                        <a href="{{ route('books.index') }}?category=probandho-o-gobeshona" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">প্রবন্ধ ও গবেষণা</a>
                        <a href="{{ route('books.index') }}?category=golpo-uponnas-bhraman" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">গল্প/উপন্যাস/ভ্রমণ</a>
                        <a href="{{ route('books.index') }}?category=natok" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">নাটক</a>
                        <a href="{{ route('books.index') }}?category=kobita" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">কবিতা</a>
                        <a href="{{ route('books.index') }}?category=anubad" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">অনুবাদ</a>
                        <a href="{{ route('books.index') }}?category=sastho" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">স্বাস্থ্য</a>
                        <hr class="my-2 border-neutral-200">
                        <a href="{{ route('books.index') }}" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">All Categories</a>
                        <a href="{{ route('books.index') }}" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">All Authors</a>
                        <a href="{{ route('book-fair.index') }}" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">Book Fair Editions</a>
                    </div>
                </div>

                {{-- Stationery Dropdown (Mobile) --}}
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-3 text-sm font-medium text-neutral-700 hover:text-neutral-900 hover:bg-neutral-50 rounded-lg transition-colors">
                        <span class="flex items-center gap-3">
                            <x-icon name="cog" class="w-5 h-5 text-neutral-400" />
                            Stationery
                        </span>
                        <x-icon name="chevron-down" class="w-5 h-5 text-neutral-400" x-bind:class="{ 'rotate-180': open }" />
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="pl-8 space-y-1 border-l border-neutral-200 ml-2 pt-2">
                        <a href="{{ route('stationeries.index') }}" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">All Stationery</a>
                        <a href="{{ route('stationeries.index') }}?sort=newest" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">New Arrivals</a>
                        <hr class="my-2 border-neutral-200">
                        <a href="{{ route('stationeries.brand', 'matador') }}" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">Matador</a>
                        <a href="{{ route('stationeries.brand', 'mango') }}" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">Mango</a>
                        <a href="{{ route('stationeries.brand', 'deli') }}" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">Deli</a>
                        <a href="{{ route('stationeries.brand', 'signature') }}" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">Signature</a>
                        <hr class="my-2 border-neutral-200">
                        <a href="{{ route('stationeries.index') }}" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">All Brands</a>
                    </div>
                </div>

                {{-- Blog Dropdown (Mobile) --}}
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-3 text-sm font-medium text-neutral-700 hover:text-neutral-900 hover:bg-neutral-50 rounded-lg transition-colors">
                        <span class="flex items-center gap-3">
                            <x-icon name="document" class="w-5 h-5 text-neutral-400" />
                            Blog
                        </span>
                        <x-icon name="chevron-down" class="w-5 h-5 text-neutral-400" x-bind:class="{ 'rotate-180': open }" />
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="pl-8 space-y-1 border-l border-neutral-200 ml-2 pt-2">
                        <a href="{{ route('blog.index') }}" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">All Posts</a>
                        <hr class="my-2 border-neutral-200">
                        <a href="{{ route('blog.index') }}?category=book-reviews" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">Book Reviews</a>
                        <a href="{{ route('blog.index') }}?category=author-interviews" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">Author Interviews</a>
                        <a href="{{ route('blog.index') }}?category=literary-news" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">Literary News</a>
                        <a href="{{ route('blog.index') }}?category=reading-tips" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">Reading Tips</a>
                        <hr class="my-2 border-neutral-200">
                        <a href="{{ route('blog.index') }}?category=all" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">All Categories</a>
                        <a href="{{ route('blog.index') }}?tag=all" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">All Tags</a>
                    </div>
                </div>

                {{-- More Dropdown (Mobile) --}}
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-3 text-sm font-medium text-neutral-700 hover:text-neutral-900 hover:bg-neutral-50 rounded-lg transition-colors">
                        <span class="flex items-center gap-3">
                            <x-icon name="ellipsis" class="w-5 h-5 text-neutral-400" />
                            More
                        </span>
                        <x-icon name="chevron-down" class="w-5 h-5 text-neutral-400" x-bind:class="{ 'rotate-180': open }" />
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="pl-8 space-y-1 border-l border-neutral-200 ml-2 pt-2">
                        <a href="{{ route('events.index') }}" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">
                            <x-icon name="calendar" class="w-4 h-4 mr-2" /> Events
                        </a>
                        <a href="{{ route('book-fair.index') }}" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">
                            <x-icon name="book" class="w-4 h-4 mr-2" /> Book Fair
                        </a>
                        <hr class="my-2 border-neutral-200">
                        <a href="{{ route('publication') }}" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">
                            <x-icon name="book" class="w-4 h-4 mr-2" /> Submit Manuscript
                        </a>
                        <hr class="my-2 border-neutral-200">
                        <a href="{{ route('about') }}" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">
                            <x-icon name="info" class="w-4 h-4 mr-2" /> About Us
                        </a>
                        <a href="{{ route('contact') }}" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">
                            <x-icon name="mail" class="w-4 h-4 mr-2" /> Contact
                        </a>
                        <hr class="my-2 border-neutral-200">
                        <a href="{{ route('privacy-policy') }}" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">Privacy Policy</a>
                        <a href="{{ route('terms-conditions') }}" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">Terms & Conditions</a>
                        <a href="{{ route('return-refund') }}" class="block px-3 py-2 text-sm text-neutral-600 hover:text-neutral-900 hover:bg-neutral-50 rounded transition-colors">Return & Refund</a>
                    </div>
                </div>

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
            </nav>
        </div>
    </div>

    <script>
        function dropdownNavigation() {
            return {
                dropdowns: { books: false, stationery: false, blog: false, more: false },
                timers: {},

                openDropdown(name) {
                    clearTimeout(this.timers[name]);
                    this.dropdowns[name] = true;
                },

                closeDropdown(name) {
                    this.timers[name] = setTimeout(() => {
                        this.dropdowns[name] = false;
                    }, 150);
                }
            }
        }
    </script>
</div>