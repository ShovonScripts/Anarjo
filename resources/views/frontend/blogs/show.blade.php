@extends('layouts.frontend')

@section('title', $blog->meta_title ?: ($blog->title . ' - ' . config('app.name')))
@section('meta_description', $blog->meta_description)

@section('content')
<article class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
        {{-- Main Content --}}
        <div class="lg:col-span-3">
            {{-- Featured Image --}}
            @if($blog->featured_image)
                <div class="mb-8">
                    <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="{{ $blog->title }}" class="w-full h-72 md:h-96 object-cover border border-neutral-200">
                </div>
            @endif

            {{-- Meta --}}
            <div class="flex flex-wrap items-center gap-4 text-xs uppercase tracking-widest text-neutral-500 mb-6">
                <time datetime="{{ $blog->published_at?->toIso8601String() }}">{{ $blog->published_at?->format('F d, Y') }}</time>
                @if($blog->category)
                    <a href="{{ route('blog.index', ['category' => $blog->category->slug]) }}" class="text-brand-600 hover:text-brand-700 font-semibold">{{ $blog->category->name }}</a>
                @endif
                <span>By {{ $blog->author ?? 'Anarjo' }}</span>
            </div>

            {{-- Title --}}
            <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight leading-tight mb-8">{{ $blog->title }}</h1>

            {{-- Tags --}}
            @if($blog->tags->count())
                <div class="flex flex-wrap gap-2 mb-8">
                    @foreach($blog->tags as $tag)
                        <span class="text-xs font-semibold uppercase tracking-wider border border-neutral-300 px-3 py-1">{{ $tag->name }}</span>
                    @endforeach
                </div>
            @endif

            {{-- Content --}}
            <div class="prose prose-neutral max-w-none mb-12 leading-relaxed blog-content">
                {!! $blog->content !!}
            </div>

            {{-- Share --}}
            <div class="border-t border-neutral-200 pt-8 flex items-center gap-4">
                <span class="text-xs font-semibold uppercase tracking-widest text-neutral-500">Share:</span>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="text-sm font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">Facebook</a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($blog->title) }}" target="_blank" class="text-sm font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">Twitter</a>
                <a href="https://www.linkedin.com/shareArticle?url={{ urlencode(request()->url()) }}" target="_blank" class="text-sm font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">LinkedIn</a>
            </div>

            {{-- Related Posts --}}
            @if($related->count())
                <div class="mt-16 border-t border-neutral-200 pt-12">
                    <h2 class="section-heading mb-8">Related Articles</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-0 border-t border-l border-neutral-200">
                        @foreach($related as $r)
                            <a href="{{ route('blog.show', $r->slug) }}" class="group border-r border-b border-neutral-200 p-6 hover:bg-black transition-colors duration-300">
                                @if($r->featured_image)
                                    <div class="h-32 overflow-hidden mb-4 -mx-6 -mt-6">
                                        <img src="{{ asset('storage/' . $r->featured_image) }}" alt="{{ $r->title }}" class="w-full h-full object-cover">
                                    </div>
                                @endif
                                <p class="text-xs uppercase tracking-widest text-neutral-400 mb-2 group-hover:text-neutral-500">{{ $r->published_at?->format('M d, Y') }}</p>
                                <p class="font-bold group-hover:text-white transition-colors">{{ $r->title }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <aside class="space-y-8">
            @if($categories->count())
                <div class="border border-neutral-200 p-6">
                    <h3 class="text-xs font-semibold uppercase tracking-widest mb-4">Categories</h3>
                    <div class="space-y-2">
                        <a href="{{ route('blog.index') }}" class="block text-sm text-neutral-600 hover:text-black transition-colors">All</a>
                        @foreach($categories as $cat)
                            <a href="{{ route('blog.index', ['category' => $cat->slug]) }}" class="block text-sm text-neutral-600 hover:text-black transition-colors">
                                {{ $cat->name }}
                                <span class="text-xs text-neutral-400">({{ $cat->blogs_count }})</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

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
</article>
@endsection

@push('styles')
<style>
.blog-content h1 { font-size: 2rem; font-weight: 800; margin-top: 2rem; margin-bottom: 1rem; }
.blog-content h2 { font-size: 1.5rem; font-weight: 700; margin-top: 1.75rem; margin-bottom: 0.75rem; }
.blog-content h3 { font-size: 1.25rem; font-weight: 600; margin-top: 1.5rem; margin-bottom: 0.5rem; }
.blog-content p { margin-bottom: 1.25rem; line-height: 1.75; }
.blog-content ul, .blog-content ol { margin-bottom: 1.25rem; padding-left: 1.5rem; }
.blog-content li { margin-bottom: 0.5rem; }
.blog-content blockquote { border-left: 4px solid #dc2626; padding-left: 1rem; margin: 1.5rem 0; color: #525252; font-style: italic; }
.blog-content img { max-width: 100%; height: auto; margin: 1.5rem 0; border: 1px solid #e5e5e5; }
.blog-content a { color: #dc2626; text-decoration: underline; }
.blog-content a:hover { color: #b91c1c; }
.blog-content pre { background: #f5f5f5; padding: 1rem; overflow-x: auto; border: 1px solid #e5e5e5; margin: 1.5rem 0; }
.blog-content code { font-size: 0.875rem; }
.blog-content table { width: 100%; border-collapse: collapse; margin: 1.5rem 0; }
.blog-content th, .blog-content td { border: 1px solid #e5e5e5; padding: 0.75rem; text-align: left; }
.blog-content th { background: #f5f5f5; font-weight: 600; }
</style>
@endpush
