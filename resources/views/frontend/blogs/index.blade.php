@extends('layouts.frontend')

@section('title', 'Blog - ' . config('app.name'))

@section('content')
<x-breadcrumbs :items="[['label' => 'Blog']]" />
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-baseline md:justify-between mb-12">
        <div>
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight">Our Blog</h1>
            <p class="mt-2 text-neutral-500">Thoughts, stories, and ideas from Anarjo.</p>
        </div>
        <form method="GET" action="{{ route('blog.index') }}" class="mt-4 md:mt-0 flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search posts..." class="input-field w-48">
            <button type="submit" class="btn-outline btn-sm">Search</button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
        {{-- Main Content --}}
        <div class="lg:col-span-3">
            @if(request('search') || request('category'))
                <p class="text-sm text-neutral-500 mb-6">
                    Showing results for
                    @if(request('search')) search: "<strong>{{ request('search') }}</strong>"@endif
                    @if(request('category')) category: "<strong>{{ request('category') }}</strong>"@endif
                    <a href="{{ route('blog.index') }}" class="text-brand-600 hover:text-brand-700 ml-2">Clear</a>
                </p>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-0 border-t border-l border-neutral-200">
                @forelse($blogs as $blog)
                    <a href="{{ route('blog.show', $blog->slug) }}" class="group border-r border-b border-neutral-200 hover:bg-black transition-colors duration-300 {{ $blog->featured_image ? 'p-0' : 'p-8' }}">
                        @if($blog->featured_image)
                            <div class="h-48 overflow-hidden">
                                <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:opacity-90 transition-opacity">
                            </div>
                            <div class="p-8">
                                <p class="text-xs uppercase tracking-widest text-neutral-400 mb-3 group-hover:text-neutral-500">{{ $blog->published_at?->format('M d, Y') }}</p>
                                <h2 class="font-bold text-lg mb-3 group-hover:text-white transition-colors">{{ $blog->title }}</h2>
                                <p class="text-sm text-neutral-600 leading-relaxed group-hover:text-neutral-300 transition-colors">{{ Str::limit(strip_tags($blog->content), 150) }}</p>
                                <div class="flex items-center gap-3 mt-4">
                                    @if($blog->category)
                                        <span class="text-xs font-semibold uppercase tracking-wider text-brand-600">{{ $blog->category->name }}</span>
                                    @endif
                                    <span class="text-xs text-neutral-400 group-hover:text-neutral-500">By {{ $blog->author ?? 'Anarjo' }}</span>
                                </div>
                            </div>
                        @else
                            <div class="p-8 flex flex-col h-full">
                                <p class="text-xs uppercase tracking-widest text-neutral-400 mb-3 group-hover:text-neutral-500">{{ $blog->published_at?->format('M d, Y') }}</p>
                                <h2 class="font-bold text-lg mb-3 group-hover:text-white transition-colors">{{ $blog->title }}</h2>
                                <p class="text-sm text-neutral-600 leading-relaxed group-hover:text-neutral-300 transition-colors flex-1">{{ Str::limit(strip_tags($blog->content), 150) }}</p>
                                <div class="flex items-center gap-3 mt-4">
                                    @if($blog->category)
                                        <span class="text-xs font-semibold uppercase tracking-wider text-brand-600">{{ $blog->category->name }}</span>
                                    @endif
                                    <span class="text-xs text-neutral-400 group-hover:text-neutral-500">By {{ $blog->author ?? 'Anarjo' }}</span>
                                </div>
                            </div>
                        @endif
                    </a>
                @empty
                    <div class="col-span-2 text-center py-20 text-neutral-500 border-r border-b border-neutral-200">No blog posts found.</div>
                @endforelse
            </div>
            <div class="mt-12">{{ $blogs->links() }}</div>
        </div>

        {{-- Sidebar --}}
        <aside class="space-y-8">
            {{-- Categories --}}
            @if($categories->count())
                <div class="border border-neutral-200 p-6">
                    <h3 class="text-xs font-semibold uppercase tracking-widest mb-4">Categories</h3>
                    <div class="space-y-2">
                        <a href="{{ route('blog.index') }}" class="block text-sm {{ request('category') ? 'text-neutral-600 hover:text-black' : 'text-black font-semibold' }} transition-colors">All</a>
                        @foreach($categories as $cat)
                            <a href="{{ route('blog.index', ['category' => $cat->slug]) }}" class="block text-sm {{ request('category') === $cat->slug ? 'text-black font-semibold' : 'text-neutral-600 hover:text-black' }} transition-colors">
                                {{ $cat->name }}
                                <span class="text-xs text-neutral-400">({{ $cat->blogs_count }})</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Recent Posts --}}
            @if($recentPosts->count())
                <div class="border border-neutral-200 p-6">
                    <h3 class="text-xs font-semibold uppercase tracking-widest mb-4">Recent Posts</h3>
                    <div class="space-y-4">
                        @foreach($recentPosts as $post)
                            <a href="{{ route('blog.show', $post->slug) }}" class="block group">
                                <p class="text-xs uppercase tracking-widest text-neutral-400 group-hover:text-neutral-500">{{ $post->published_at?->format('M d, Y') }}</p>
                                <p class="text-sm font-semibold group-hover:text-brand-600 transition-colors">{{ $post->title }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>
    </div>
</div>
@endsection
