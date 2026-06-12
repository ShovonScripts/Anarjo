<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;

class AboutController extends Controller
{
    public function index()
    {
        $stats = [
            'books' => Book::where('status', 'published')->count(),
            'authors' => Author::where('status', 'active')->count(),
            'categories' => Category::count(),
        ];

        return view('frontend.about', compact('stats'));
    }
}
