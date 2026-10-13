<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BookFairEdition;
use Illuminate\View\View;

class BookFairController extends Controller
{
    public function index(): View
    {
        $editions = BookFairEdition::where('status', 'published')
            ->withCount('books')
            ->latest()
            ->paginate(12);

        return view('frontend.book-fair.index', compact('editions'));
    }

    public function show($slug): View
    {
        $edition = BookFairEdition::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $books = $edition->books()
            ->with('author', 'categories')
            ->where('status', 'published')
            ->latest()
            ->paginate(12);

        return view('frontend.book-fair.show', compact('edition', 'books'));
    }
}
