<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Anarjo')) - Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @if (setting('favicon'))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . setting('favicon')) }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.min.js"></script>
    @stack('head')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-neutral-50 flex">
        {{-- Sidebar --}}
        <aside class="w-64 bg-black border-r border-neutral-800 fixed h-full overflow-y-auto">
            <div class="px-6 py-5 border-b border-neutral-800">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    @if (setting('header_logo'))
                        <img src="{{ asset('storage/' . setting('header_logo')) }}" alt="Logo" class="h-8 brightness-0 invert">
                    @endif
                    <span class="text-xl font-extrabold tracking-tight text-white">{{ setting('site_name', config('app.name', 'Anarjo')) }}</span>
                </a>
            </div>
            <nav class="p-4 space-y-0.5">
                <x-admin.nav-item :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" label="Dashboard" />
                {{-- Books Dropdown --}}
                @php $booksActive = request()->routeIs('admin.books*') || request()->routeIs('admin.categories*') || request()->routeIs('admin.authors*'); @endphp
                <div x-data="{ open: @json($booksActive) }" class="space-y-0.5">
                    <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2.5 text-sm font-medium transition-colors {{ $booksActive ? 'text-white bg-brand-600' : 'text-neutral-400 hover:text-white hover:bg-neutral-800' }}">
                        <span>Books</span>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-collapse class="space-y-0.5">
                        <a href="{{ route('admin.books') }}" wire:navigate class="flex items-center px-6 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.books*') ? 'text-white bg-brand-600' : 'text-neutral-400 hover:text-white hover:bg-neutral-800' }}">All Books</a>
                        <a href="{{ route('admin.categories') }}" wire:navigate class="flex items-center px-6 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.categories*') ? 'text-white bg-brand-600' : 'text-neutral-400 hover:text-white hover:bg-neutral-800' }}">Categories</a>
                        <a href="{{ route('admin.authors') }}" wire:navigate class="flex items-center px-6 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.authors*') ? 'text-white bg-brand-600' : 'text-neutral-400 hover:text-white hover:bg-neutral-800' }}">Authors</a>
                    </div>
                </div>
                <x-admin.nav-item :href="route('admin.orders')" :active="request()->routeIs('admin.orders*')" label="Orders" />
                <x-admin.nav-item :href="route('admin.users')" :active="request()->routeIs('admin.users*')" label="Users" />
                <x-admin.nav-item :href="route('admin.reviews')" :active="request()->routeIs('admin.reviews*')" label="Reviews" />
                <x-admin.nav-item :href="route('admin.coupons')" :active="request()->routeIs('admin.coupons*')" label="Coupons" />
                {{-- Blogs Dropdown --}}
                @php $blogsActive = request()->routeIs('admin.blogs*') || request()->routeIs('admin.blog-categories*') || request()->routeIs('admin.blog-tags*'); @endphp
                <div x-data="{ open: @json($blogsActive) }" class="space-y-0.5">
                    <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2.5 text-sm font-medium transition-colors {{ $blogsActive ? 'text-white bg-brand-600' : 'text-neutral-400 hover:text-white hover:bg-neutral-800' }}">
                        <span>Blogs</span>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-collapse class="space-y-0.5">
                        <a href="{{ route('admin.blogs') }}" wire:navigate class="flex items-center px-6 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.blogs*') ? 'text-white bg-brand-600' : 'text-neutral-400 hover:text-white hover:bg-neutral-800' }}">Blog Posts</a>
                        <a href="{{ route('admin.blog-categories') }}" wire:navigate class="flex items-center px-6 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.blog-categories*') ? 'text-white bg-brand-600' : 'text-neutral-400 hover:text-white hover:bg-neutral-800' }}">Categories</a>
                        <a href="{{ route('admin.blog-tags') }}" wire:navigate class="flex items-center px-6 py-2 text-sm font-medium transition-colors {{ request()->routeIs('admin.blog-tags*') ? 'text-white bg-brand-600' : 'text-neutral-400 hover:text-white hover:bg-neutral-800' }}">Tags</a>
                    </div>
                </div>
                <x-admin.nav-item :href="route('admin.events')" :active="request()->routeIs('admin.events*')" label="Events" />
                <x-admin.nav-item :href="route('admin.media')" :active="request()->routeIs('admin.media*')" label="Media" />
                <x-admin.nav-item :href="route('admin.manuscripts')" :active="request()->routeIs('admin.manuscripts*')" label="Manuscripts" />
                <x-admin.nav-item :href="route('admin.newsletter')" :active="request()->routeIs('admin.newsletter*')" label="Newsletter" />
                <x-admin.nav-item :href="route('admin.seo')" :active="request()->routeIs('admin.seo*')" label="SEO" />
                <x-admin.nav-item :href="route('admin.reports')" :active="request()->routeIs('admin.reports*')" label="Reports" />
                <x-admin.nav-item :href="route('admin.settings')" :active="request()->routeIs('admin.settings*')" label="Settings" />
            </nav>
        </aside>
        <main class="flex-1 ml-64">
            <header class="bg-white border-b border-neutral-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <h2 class="text-lg font-bold tracking-tight">@yield('header', $header ?? 'Dashboard')</h2>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-neutral-500">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm font-semibold uppercase tracking-wider text-brand-600 hover:text-brand-700 transition-colors">Logout</button>
                        </form>
                    </div>
                </div>
            </header>
            <div class="p-6">
                @yield('content')
                {{ $slot ?? '' }}
            </div>
        </main>
    </div>
    @stack('js')
</body>
</html>
