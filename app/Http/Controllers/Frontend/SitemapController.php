<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Blog;
use App\Models\Author;
use App\Models\Category;

class SitemapController extends Controller
{
    public function index()
    {
        $books = Book::where('status', 'published')->select('slug', 'updated_at')->get();
        $blogs = Blog::where('status', 'published')->select('slug', 'updated_at')->get();
        $authors = Author::where('status', 'active')->select('slug', 'updated_at')->get();
        $categories = Category::select('slug', 'updated_at')->get();

        return response()->view('seo.sitemap', compact(
            'books', 'blogs', 'authors', 'categories'
        ))->header('Content-Type', 'text/xml');
    }
}
