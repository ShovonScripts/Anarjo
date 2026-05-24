<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;

class BlogController extends Controller
{
    public function index()
    {
        $query = Blog::with('category')->where('status', 'published');

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($category = request('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $category));
        }

        $blogs = $query->latest()->paginate(9);
        $categories = BlogCategory::withCount(['blogs' => fn ($q) => $q->where('status', 'published')])
            ->having('blogs_count', '>', 0)
            ->get();
        $recentPosts = Blog::where('status', 'published')->latest()->take(4)->get();

        return view('frontend.blogs.index', compact('blogs', 'categories', 'recentPosts'));
    }

    public function show($slug)
    {
        $blog = Blog::with(['category', 'tags'])->where('slug', $slug)->where('status', 'published')->firstOrFail();

        $related = Blog::where('status', 'published')
            ->where('id', '!=', $blog->id)
            ->when($blog->blog_category_id, fn ($q) => $q->where('blog_category_id', $blog->blog_category_id))
            ->latest()
            ->take(3)
            ->get();

        $recentPosts = Blog::where('status', 'published')
            ->where('id', '!=', $blog->id)
            ->latest()
            ->take(4)
            ->get();

        $categories = BlogCategory::withCount(['blogs' => fn ($q) => $q->where('status', 'published')])
            ->having('blogs_count', '>', 0)
            ->get();

        return view('frontend.blogs.show', compact('blog', 'related', 'recentPosts', 'categories'));
    }
}
