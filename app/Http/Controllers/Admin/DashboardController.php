<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DeliveryStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Blog;
use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
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
            'revenue' => Order::where('payment_status', PaymentStatus::Completed->value)->sum('total'),
            'pending_orders' => Order::where('delivery_status', DeliveryStatus::Pending->value)->count(),
            'processing_orders' => Order::where('delivery_status', DeliveryStatus::Processing->value)->count(),
            'delivered_orders' => Order::where('delivery_status', DeliveryStatus::Delivered->value)->count(),
            'cancelled_orders' => Order::where('delivery_status', DeliveryStatus::Cancelled->value)->count(),

        ];

        $recentOrders = Order::withCount('items')
            ->latest()
            ->take(5)
            ->get();

        $dateFormat = DB::getDriverName() === 'sqlite'
            ? "strftime('%Y-%m', created_at)"
            : "DATE_FORMAT(created_at, '%Y-%m')";

        $monthlyRevenue = Order::where('payment_status', PaymentStatus::Completed->value)
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->select(
                DB::raw("{$dateFormat} as month"),
                DB::raw('COALESCE(SUM(total), 0) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $monthlyOrders = Order::where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->select(
                DB::raw("{$dateFormat} as month"),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $months->push(now()->subMonths($i));
        }

        $monthLabels = $months->map(fn ($d) => $d->format('M'))->toArray();
        $revenueData = $months->map(fn ($d) => (float) ($monthlyRevenue->get($d->format('Y-m'))?->total ?? 0))->toArray();
        $ordersData = $months->map(fn ($d) => (int) ($monthlyOrders->get($d->format('Y-m'))?->total ?? 0))->toArray();

        $orderStatuses = Order::select('delivery_status', DB::raw('COUNT(*) as total'))
            ->groupBy('delivery_status')
            ->pluck('total', 'delivery_status');

        $topCategories = Category::withCount('books')
            ->orderBy('books_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'recentOrders', 'monthLabels', 'revenueData',
            'ordersData', 'orderStatuses', 'topCategories'
        ));
    }
}
