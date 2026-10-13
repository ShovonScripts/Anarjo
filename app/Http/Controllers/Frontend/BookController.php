<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Support\Sorting;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(): View
    {
        $search = request('search');
        $sort = request('sort', 'most-visited');

        $books = Book::with('author', 'categories')
            ->where('status', 'published');

        if ($search) {
            $books->search($search);
        }

        Sorting::apply($books, $sort);

        $categories = Category::withCount('books')->get();
        $authors = Author::where('status', 'active')->withCount('books')->whereHas('books')->orderBy('books_count', 'desc')->get();

        $books = $books->paginate(12);

        return view('frontend.books.index', compact('books', 'categories', 'authors', 'sort'));
    }

    public function show($slug): View
    {
        $book = Book::with('author', 'categories', 'bookFairEditions', 'reviews.user', 'comments.user', 'likes')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $book->increment('views');

        $categoryIds = $book->categories->pluck('id');

        $relatedBooks = Book::with('author', 'categories')
            ->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $categoryIds))
            ->where('id', '!=', $book->id)
            ->where('status', 'published')
            ->latest()
            ->take(6)
            ->get();

        return view('frontend.books.show', compact('book', 'relatedBooks'));
    }

    public function category($slug): View
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $sort = request('sort', 'most-visited');

        $books = Book::with('author', 'categories')
            ->whereHas('categories', fn ($q) => $q->where('categories.id', $category->id))
            ->where('status', 'published');

        Sorting::apply($books, $sort);

        $categories = Category::withCount('books')->get();
        $authors = Author::where('status', 'active')->withCount('books')->whereHas('books')->orderBy('books_count', 'desc')->get();

        $books = $books->paginate(12);

        return view('frontend.books.index', compact('books', 'categories', 'category', 'authors', 'sort'));
    }

    public function byAuthor($slug): View
    {
        $author = Author::where('slug', $slug)->where('status', 'active')->firstOrFail();
        $sort = request('sort', 'most-visited');

        $books = Book::with('author', 'categories')
            ->where('author_id', $author->id)
            ->where('status', 'published');

        Sorting::apply($books, $sort);

        $categories = Category::withCount('books')->get();
        $authors = Author::where('status', 'active')->withCount('books')->whereHas('books')->orderBy('books_count', 'desc')->get();

        $books = $books->paginate(12);

        return view('frontend.books.index', compact('books', 'categories', 'authors', 'author', 'sort'));
    }
}
