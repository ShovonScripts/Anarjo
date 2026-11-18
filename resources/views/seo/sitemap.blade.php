<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <priority>1.0</priority>
        <changefreq>daily</changefreq>
    </url>
    <url>
        <loc>{{ route('books.index') }}</loc>
        <priority>0.9</priority>
        <changefreq>daily</changefreq>
    </url>
    <url>
        <loc>{{ route('about') }}</loc>
        <priority>0.7</priority>
        <changefreq>monthly</changefreq>
    </url>
    <url>
        <loc>{{ route('blog.index') }}</loc>
        <priority>0.8</priority>
        <changefreq>weekly</changefreq>
    </url>
    <url>
        <loc>{{ route('events.index') }}</loc>
        <priority>0.6</priority>
        <changefreq>weekly</changefreq>
    </url>
    <url>
        <loc>{{ route('publication') }}</loc>
        <priority>0.6</priority>
        <changefreq>monthly</changefreq>
    </url>
    @foreach($books as $book)
        <url>
            <loc>{{ route('books.show', $book->slug) }}</loc>
            @if($book->updated_at)<lastmod>{{ $book->updated_at->tz('UTC')->toW3cString() }}</lastmod>@endif
            <priority>0.8</priority>
            <changefreq>weekly</changefreq>
        </url>
    @endforeach
    @foreach($blogs as $blog)
        <url>
            <loc>{{ route('blog.show', $blog->slug) }}</loc>
            @if($blog->updated_at)<lastmod>{{ $blog->updated_at->tz('UTC')->toW3cString() }}</lastmod>@endif
            <priority>0.7</priority>
            <changefreq>monthly</changefreq>
        </url>
    @endforeach
    @foreach($authors as $author)
        <url>
            <loc>{{ route('author.show', $author->slug) }}</loc>
            @if($author->updated_at)<lastmod>{{ $author->updated_at->tz('UTC')->toW3cString() }}</lastmod>@endif
            <priority>0.6</priority>
            <changefreq>monthly</changefreq>
        </url>
    @endforeach
    @foreach($categories as $category)
        <url>
            <loc>{{ route('category.books', $category->slug) }}</loc>
            <priority>0.5</priority>
            <changefreq>weekly</changefreq>
        </url>
    @endforeach
</urlset>
