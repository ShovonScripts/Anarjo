<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\HeroSlide;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        $stats = [
            'books' => Book::where('status', 'published')->count(),
            'authors' => Author::where('status', 'active')->count(),
            'categories' => Category::count(),
        ];

        $aboutSlides = HeroSlide::where('page', 'about')
            ->where('status', true)
            ->orderBy('sort_order')
            ->get();

        return view('frontend.about', compact('stats', 'aboutSlides'));
    }
}
