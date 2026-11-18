<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\View\View;

class AuthorController extends Controller
{
    public function show($slug): View
    {
        $author = Author::where('slug', $slug)->where('status', 'active')->firstOrFail();
        $books = $author->books()->where('status', 'published')->latest()->paginate(12);

        return view('frontend.authors.show', compact('author', 'books'));
    }
}
