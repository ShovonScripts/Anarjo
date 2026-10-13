<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Blog;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index(): Response
    {
        [$books, $blogs, $authors, $categories] = Cache::remember('sitemap.data', 3600, function () {
            return [
                Book::where('status', 'published')->select('slug', 'updated_at')->get(),
                Blog::where('status', 'published')->select('slug', 'updated_at')->get(),
                Author::where('status', 'active')->select('slug', 'updated_at')->get(),
                Category::select('slug', 'updated_at')->get(),
            ];
        });

        return response()->view('seo.sitemap', compact(
            'books', 'blogs', 'authors', 'categories'
        ))->header('Content-Type', 'text/xml');
    }
}
