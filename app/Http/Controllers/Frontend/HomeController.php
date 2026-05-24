<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Blog;
use App\Models\Author;

class HomeController extends Controller
{
    public function index()
    {
        $featuredBooks = Book::with('author')->where('status', 'published')->where('featured', true)->latest()->take(8)->get();
        $newArrivals = Book::with('author')->where('status', 'published')->where('new_arrival', true)->latest()->take(8)->get();
        $bestsellers = Book::with('author')->where('status', 'published')->where('bestseller', true)->latest()->take(8)->get();
        $latestBlogs = Blog::where('status', 'published')->latest()->take(6)->get();
        $authors = Author::where('status', 'active')->latest()->take(10)->get();

        return view('frontend.home', compact(
            'featuredBooks', 'newArrivals', 'bestsellers',
            'latestBlogs', 'authors'
        ));
    }
}
