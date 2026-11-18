<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Blog;
use App\Models\Book;
use App\Models\BookFairEdition;
use App\Models\HeroSlide;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $heroSlides = HeroSlide::where('page', 'home')->where('status', true)->orderBy('sort_order')->get();
        $featuredBooks = Book::with(['author', 'categories'])->where('status', 'published')->where('featured', true)->latest()->take(20)->get();

        if ($featuredBooks->isEmpty()) {
            $featuredBooks = Book::with(['author', 'categories'])->where('status', 'published')->latest()->take(20)->get();
        }
        $discountedBooks = Book::with(['author', 'categories'])->where('status', 'published')->whereNotNull('sale_price')->where('price', '>', 0)->orderByRaw('((price - sale_price) / price) desc')->take(20)->get();
        $popularBooks = Book::with(['author', 'categories'])->where('status', 'published')->orderBy('views', 'desc')->take(20)->get();
        $newArrivals = Book::with('author', 'categories')->where('status', 'published')->where('new_arrival', true)->latest()->take(8)->get();
        $latestBlogs = Blog::where('status', 'published')->latest()->take(6)->get();
        $authors = Author::where('status', 'active')
            ->whereNotIn('slug', ['mrs-elza-stokes', 'nicholaus-robel'])
            ->whereHas('books', fn ($q) => $q->where('status', 'published'))
            ->withCount(['books' => fn ($q) => $q->where('status', 'published')])
            ->orderByDesc('books_count')
            ->orderBy('name')
            ->take(16)
            ->get();
        $currentFairEdition = BookFairEdition::where('is_current', true)->where('status', 'published')->first();
        $fairEditionBooks = $currentFairEdition
            ? Book::with('author', 'categories')
                ->whereHas('bookFairEditions', fn ($q) => $q->where('book_fair_edition_id', $currentFairEdition->id))
                ->where('status', 'published')
                ->latest()
                ->take(8)
                ->get()
            : collect();

        return view('frontend.home', compact(
            'heroSlides', 'featuredBooks', 'discountedBooks', 'popularBooks', 'newArrivals',
            'latestBlogs', 'authors',
            'currentFairEdition', 'fairEditionBooks',
        ));
    }
}
