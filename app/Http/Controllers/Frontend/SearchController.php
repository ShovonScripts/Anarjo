<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Blog;
use App\Models\Book;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(): RedirectResponse|View
    {
        $query = request('q');

        if (! $query || strlen(trim($query)) < 1) {
            return redirect()->route('home')->with('error', 'Please enter a search term.');
        }

        $books = Book::with('author')
            ->where('status', 'published')
            ->search($query)
            ->latest()
            ->take(8)
            ->get();

        $authors = Author::where('status', 'active')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('bio', 'like', "%{$query}%");
            })
            ->latest()
            ->take(4)
            ->get();

        $blogs = Blog::with('category')
            ->where('status', 'published')
            ->search($query)
            ->latest()
            ->take(4)
            ->get();

        $events = Event::where('status', 'published')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->latest()
            ->take(4)
            ->get();

        $totalResults = $books->count() + $authors->count() + $blogs->count() + $events->count();

        return view('frontend.search.index', compact(
            'query', 'books', 'authors', 'blogs', 'events', 'totalResults'
        ));
    }
}
