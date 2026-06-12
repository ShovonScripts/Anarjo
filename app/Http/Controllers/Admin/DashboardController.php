<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use App\Models\Author;
use App\Models\User;
use App\Models\Review;
use App\Models\Blog;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_books' => Book::count(),
            'total_orders' => Order::count(),
            'total_authors' => Author::count(),
            'total_users' => User::count(),
            'total_reviews' => Review::count(),
            'pending_reviews' => Review::where('status', 'pending')->count(),
            'total_blogs' => Blog::count(),
            'total_categories' => Category::count(),
            'revenue' => Order::where('payment_status', 'completed')->sum('total'),
            'pending_orders' => Order::where('delivery_status', 'pending')->count(),
        ];

        $recentOrders = Order::withCount('items')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}
