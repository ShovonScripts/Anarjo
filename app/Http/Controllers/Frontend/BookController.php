<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;

class BookController extends Controller
{
    public function index()
    {
        $search = request('search');

        $books = Book::with('author', 'category')
            ->where('status', 'published');

        if ($search) {
            $books->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categories = Category::withCount('books')->get();

        $books = $books->latest()->paginate(12);

        return view('frontend.books.index', compact('books', 'categories'));
    }

    public function show($slug)
    {
        $book = Book::with('author', 'category', 'reviews.user', 'comments.user', 'likes')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $relatedBooks = Book::with('author')
            ->where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->where('status', 'published')
            ->latest()
            ->take(6)
            ->get();

        return view('frontend.books.show', compact('book', 'relatedBooks'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $books = Book::with('author', 'category')
            ->where('category_id', $category->id)
            ->where('status', 'published')
            ->latest()
            ->paginate(12);

        $categories = Category::withCount('books')->get();

        return view('frontend.books.index', compact('books', 'categories', 'category'));
    }
}
