<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Blog;
use App\Models\Book;
use App\Models\Category;
use App\Models\Event;
use App\Models\HeroSlide;

class HomeController extends Controller
{
    public function index()
    {
        $heroSlides = HeroSlide::where('status', true)->orderBy('sort_order')->get();
        $categories = Category::withCount('books')->get();
        $featuredBooks = Book::with(['author', 'category'])->where('status', 'published')->where('featured', true)->latest()->take(20)->get();
        $newArrivals = Book::with('author', 'category')->where('status', 'published')->where('new_arrival', true)->latest()->take(8)->get();
        $bestsellers = Book::with('author', 'category')->where('status', 'published')->where('bestseller', true)->latest()->take(8)->get();
        $upcomingEvents = Event::where('status', 'published')->where('date', '>=', now())->orderBy('date')->take(3)->get();
        $latestBlogs = Blog::where('status', 'published')->latest()->take(6)->get();
        $authors = Author::where('status', 'active')->latest()->take(10)->get();

        $stats = [
            'books' => Book::where('status', 'published')->count(),
            'authors' => Author::where('status', 'active')->count(),
            'categories' => Category::count(),
        ];

        return view('frontend.home', compact(
            'heroSlides', 'categories', 'featuredBooks', 'newArrivals', 'bestsellers',
            'upcomingEvents', 'latestBlogs', 'authors', 'stats'
        ));
    }
}
