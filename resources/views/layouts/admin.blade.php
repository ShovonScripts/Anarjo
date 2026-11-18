<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Anarjo')) - Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet" />
    @if (setting('favicon'))
        <link rel="icon" type="image/png" href="{{ asset('storage/' . setting('favicon')) }}">
    @endif
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.min.js"></script>
    @stack('head')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-neutral-50 flex">
        {{-- Sidebar --}}
        <aside class="w-64 bg-[#0a0a0a] border-r border-white/[0.06] fixed h-full overflow-y-auto z-30 flex flex-col">
            {{-- Logo --}}
            <div class="flex-shrink-0 border-b border-white/[0.06]">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-5 h-16">
                    <div class="h-9 w-9 bg-gradient-to-br from-brand-500 to-brand-700 rounded-lg flex items-center justify-center text-white font-extrabold text-sm shadow-lg shadow-brand-600/20 flex-shrink-0">
                        {{ substr(setting('site_name', config('app.name', 'A')), 0, 1) }}
                    </div>
                    <div class="min-w-0">
                        <div class="text-base font-extrabold tracking-tight text-white leading-none truncate">{{ setting('site_name', config('app.name', 'Anarjo')) }}</div>
                        <div class="text-[10px] text-neutral-500 uppercase tracking-[0.2em] font-medium mt-0.5">Admin Panel</div>
                    </div>
                </a>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto py-5 px-3 space-y-6">
                {{-- Section: Main --}}
                <div>
                    <div class="flex items-center gap-3 px-4 mb-1.5">
                        <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-neutral-600">Main</span>
                        <span class="flex-1 h-px bg-white/[0.06]"></span>
                    </div>
                    <div class="space-y-0.5">
                        <x-admin.nav-item :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" label="Dashboard">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </x-admin.nav-item>
                    </div>
                </div>

                {{-- Section: Content --}}
                <div>
                    <div class="flex items-center gap-3 px-4 mb-1.5">
                        <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-neutral-600">Content</span>
                        <span class="flex-1 h-px bg-white/[0.06]"></span>
                    </div>
                    <div class="space-y-0.5">
                        {{-- Books Dropdown --}}
                        @php $booksActive = request()->routeIs('admin.books*') || request()->routeIs('admin.categories*') || request()->routeIs('admin.authors*') || request()->routeIs('admin.book-fair-editions*'); @endphp
                        <div x-data="{ open: @json($booksActive) }">
                            <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 text-sm font-medium border-l-2 transition-all duration-150 {{ $booksActive ? 'text-white bg-white/[0.07] border-brand-500' : 'text-neutral-400 hover:text-white hover:bg-white/[0.04] border-transparent' }}">
                                <span class="flex items-center gap-3 min-w-0">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    <span class="truncate">Books</span>
                                </span>
                                <svg class="w-3 h-3 shrink-0 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-0.5 space-y-0.5 pl-11">
                                <x-admin.nav-item :href="route('admin.books')" :active="request()->routeIs('admin.books')" label="All Books" />
                                <x-admin.nav-item :href="route('admin.categories')" :active="request()->routeIs('admin.categories*')" label="Categories" />
                                <x-admin.nav-item :href="route('admin.authors')" :active="request()->routeIs('admin.authors*')" label="Authors" />
                                <x-admin.nav-item :href="route('admin.book-fair-editions')" :active="request()->routeIs('admin.book-fair-editions*')" label="Book Fair Editions" />
                            </div>
                        </div>

                        {{-- Stationery Dropdown --}}
                        @php $stationeryActive = request()->routeIs('admin.stationeries*') || request()->routeIs('admin.brands*'); @endphp
                        <div x-data="{ open: @json($stationeryActive) }">
                            <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 text-sm font-medium border-l-2 transition-all duration-150 {{ $stationeryActive ? 'text-white bg-white/[0.07] border-brand-500' : 'text-neutral-400 hover:text-white hover:bg-white/[0.04] border-transparent' }}">
                                <span class="flex items-center gap-3 min-w-0">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span class="truncate">Stationery</span>
                                </span>
                                <svg class="w-3 h-3 shrink-0 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-0.5 space-y-0.5 pl-11">
                                <x-admin.nav-item :href="route('admin.stationeries')" :active="request()->routeIs('admin.stationeries')" label="All Items" />
                                <x-admin.nav-item :href="route('admin.brands')" :active="request()->routeIs('admin.brands*')" label="Brands" />
                            </div>
                        </div>

                        {{-- Blogs Dropdown --}}
                        @php $blogsActive = request()->routeIs('admin.blogs*') || request()->routeIs('admin.blog-categories*') || request()->routeIs('admin.blog-tags*'); @endphp
                        <div x-data="{ open: @json($blogsActive) }">
                            <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 text-sm font-medium border-l-2 transition-all duration-150 {{ $blogsActive ? 'text-white bg-white/[0.07] border-brand-500' : 'text-neutral-400 hover:text-white hover:bg-white/[0.04] border-transparent' }}">
                                <span class="flex items-center gap-3 min-w-0">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                    <span class="truncate">Blogs</span>
                                </span>
                                <svg class="w-3 h-3 shrink-0 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-0.5 space-y-0.5 pl-11">
                                <x-admin.nav-item :href="route('admin.blogs')" :active="request()->routeIs('admin.blogs')" label="All Posts" />
                                <x-admin.nav-item :href="route('admin.blog-categories')" :active="request()->routeIs('admin.blog-categories*')" label="Categories" />
                                <x-admin.nav-item :href="route('admin.blog-tags')" :active="request()->routeIs('admin.blog-tags*')" label="Tags" />
                            </div>
                        </div>

                        <x-admin.nav-item :href="route('admin.events')" :active="request()->routeIs('admin.events*')" label="Events">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </x-admin.nav-item>

                        <x-admin.nav-item :href="route('admin.media')" :active="request()->routeIs('admin.media*')" label="Media">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </x-admin.nav-item>

                        <x-admin.nav-item :href="route('admin.manuscripts')" :active="request()->routeIs('admin.manuscripts*')" label="Manuscripts">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </x-admin.nav-item>

                        <x-admin.nav-item :href="route('admin.contact-messages')" :active="request()->routeIs('admin.contact-messages*')" label="Contact">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </x-admin.nav-item>
                    </div>
                </div>

                {{-- Section: Commerce --}}
                <div>
                    <div class="flex items-center gap-3 px-4 mb-1.5">
                        <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-neutral-600">Commerce</span>
                        <span class="flex-1 h-px bg-white/[0.06]"></span>
                    </div>
                    <div class="space-y-0.5">
                        <x-admin.nav-item :href="route('admin.orders')" :active="request()->routeIs('admin.orders*')" label="Orders">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        </x-admin.nav-item>

                        <x-admin.nav-item :href="route('admin.coupons')" :active="request()->routeIs('admin.coupons*')" label="Coupons">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        </x-admin.nav-item>
                    </div>
                </div>

                {{-- Section: People --}}
                <div>
                    <div class="flex items-center gap-3 px-4 mb-1.5">
                        <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-neutral-600">People</span>
                        <span class="flex-1 h-px bg-white/[0.06]"></span>
                    </div>
                    <div class="space-y-0.5">
                        <x-admin.nav-item :href="route('admin.admins')" :active="request()->routeIs('admin.admins*')" label="Admins">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                        </x-admin.nav-item>

                        <x-admin.nav-item :href="route('admin.users')" :active="request()->routeIs('admin.users*')" label="Users">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                        </x-admin.nav-item>

                        <x-admin.nav-item :href="route('admin.reviews')" :active="request()->routeIs('admin.reviews*')" label="Reviews">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        </x-admin.nav-item>

                        <x-admin.nav-item :href="route('admin.comments')" :active="request()->routeIs('admin.comments*')" label="Comments">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </x-admin.nav-item>
                    </div>
                </div>

                {{-- Section: Pages --}}
                <div>
                    <div class="flex items-center gap-3 px-4 mb-1.5">
                        <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-neutral-600">Pages</span>
                        <span class="flex-1 h-px bg-white/[0.06]"></span>
                    </div>
                    <div class="space-y-0.5">
                        @php $pagesActive = request()->routeIs('admin.page-settings*'); @endphp
                        <div x-data="{ open: @json($pagesActive) }">
                            <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 text-sm font-medium border-l-2 transition-all duration-150 {{ $pagesActive ? 'text-white bg-white/[0.07] border-brand-500' : 'text-neutral-400 hover:text-white hover:bg-white/[0.04] border-transparent' }}">
                                <span class="flex items-center gap-3 min-w-0">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                                    <span class="truncate">Page Settings</span>
                                </span>
                                <svg class="w-3 h-3 shrink-0 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="mt-0.5 space-y-0.5 pl-11">
                                <x-admin.nav-item :href="route('admin.page-settings.home')" :active="request()->routeIs('admin.page-settings.home*')" label="Home Page" />
                                <x-admin.nav-item :href="route('admin.page-settings.about')" :active="request()->routeIs('admin.page-settings.about*')" label="About" />
                                <x-admin.nav-item :href="route('admin.page-settings.contact')" :active="request()->routeIs('admin.page-settings.contact*')" label="Contact" />
                                <x-admin.nav-item :href="route('admin.page-settings.policies')" :active="request()->routeIs('admin.page-settings.policies*')" label="Policies" />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Section: System --}}
                <div>
                    <div class="flex items-center gap-3 px-4 mb-1.5">
                        <span class="text-[10px] font-semibold uppercase tracking-[0.2em] text-neutral-600">System</span>
                        <span class="flex-1 h-px bg-white/[0.06]"></span>
                    </div>
                    <div class="space-y-0.5">
                        <x-admin.nav-item :href="route('admin.newsletter')" :active="request()->routeIs('admin.newsletter*')" label="Newsletter">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </x-admin.nav-item>

                        <x-admin.nav-item :href="route('admin.seo')" :active="request()->routeIs('admin.seo*')" label="SEO">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </x-admin.nav-item>

                        <x-admin.nav-item :href="route('admin.reports')" :active="request()->routeIs('admin.reports*')" label="Reports">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </x-admin.nav-item>

                        <x-admin.nav-item :href="route('admin.settings')" :active="request()->routeIs('admin.settings*')" label="Settings">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </x-admin.nav-item>
                    </div>
                </div>
            </nav>

            {{-- User Card --}}
            <div class="flex-shrink-0 border-t border-white/[0.06] p-3">
                <div class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-white/[0.04] transition-all duration-150 group">
                    <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-neutral-600 to-neutral-800 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('profile') }}" class="text-sm font-medium text-neutral-300 group-hover:text-white transition-colors truncate block">{{ auth()->user()->name }}</a>
                        <p class="text-[10px] text-neutral-600 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="shrink-0">
                        @csrf
                        <button type="submit" class="text-neutral-600 hover:text-brand-500 transition-colors p-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 ml-64">
            <header class="bg-white border-b border-neutral-200 sticky top-0 z-20">
                <div class="flex items-center justify-between px-6 py-4">
                    <h2 class="text-lg font-bold tracking-tight">@yield('header', 'Dashboard')</h2>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2 text-sm text-neutral-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            {{ auth()->user()->name }}
                        </div>
                    </div>
                </div>
            </header>
            <div class="p-6">
                @yield('content')
                {{ $slot ?? '' }}
            </div>
        </main>
    </div>
    @livewireScripts
    @stack('js')
</body>
</html>
