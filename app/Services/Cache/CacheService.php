<?php

namespace App\Services\Cache;

use App\Models\Book;
use App\Models\Author;
use App\Models\Category;
use App\Models\Blog;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    public static function rememberBooks(int $perPage = 12, array $filters = []): mixed
    {
        $key = 'books:' . md5(serialize($filters) . $perPage . request('page', 1));

        return Cache::remember($key, 3600, function () use ($perPage, $filters) {
            $query = Book::with('author', 'category')->where('status', 'published');

            if (!empty($filters['category_id'])) {
                $query->where('category_id', $filters['category_id']);
            }
            if (!empty($filters['featured'])) {
                $query->where('featured', true);
            }
            if (!empty($filters['bestseller'])) {
                $query->where('bestseller', true);
            }
            if (!empty($filters['new_arrival'])) {
                $query->where('new_arrival', true);
            }

            return $query->latest()->paginate($perPage);
        });
    }

    public static function rememberFeaturedBooks(): mixed
    {
        return Cache::remember('books:featured', 1800, function () {
            return Book::with('author')
                ->where('status', 'published')
                ->where('featured', true)
                ->latest()
                ->take(8)
                ->get();
        });
    }

    public static function rememberBestsellers(): mixed
    {
        return Cache::remember('books:bestsellers', 1800, function () {
            return Book::with('author')
                ->where('status', 'published')
                ->where('bestseller', true)
                ->latest()
                ->take(8)
                ->get();
        });
    }

    public static function rememberCategories(): mixed
    {
        return Cache::remember('categories:with-count', 3600, function () {
            return Category::withCount('books')->get();
        });
    }

    public static function rememberAuthors(): mixed
    {
        return Cache::remember('authors:active', 3600, function () {
            return Author::where('status', 'active')->latest()->take(10)->get();
        });
    }

    public static function rememberBlogs(int $limit = 6): mixed
    {
        return Cache::remember('blogs:latest', 1800, function () use ($limit) {
            return Blog::where('status', 'published')->latest()->take($limit)->get();
        });
    }

    public static function flushBooks(): void
    {
        Cache::forget('books:featured');
        Cache::forget('books:bestsellers');
        Cache::forget('books:new_arrivals');
    }

    public static function flushAll(): void
    {
        Cache::flush();
    }
}
