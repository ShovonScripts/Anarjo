<?php

namespace App\Livewire\Admin;

use App\Enums\DeliveryStatus;
use App\Enums\PaymentStatus;
use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Reports extends Component
{
    public string $period = 'monthly';

    public function render()
    {
        $driver = DB::getDriverName();
        $dateFormat = $driver === 'sqlite' ? "strftime('%Y-%m', created_at)" : "DATE_FORMAT(created_at, '%Y-%m')";
        $yearFormat = $driver === 'sqlite' ? "strftime('%Y', created_at)" : 'YEAR(created_at)';
        $dayFormat = $driver === 'sqlite' ? "strftime('%Y-%m-%d', created_at)" : 'DATE(created_at)';

        $labelExpr = match ($this->period) {
            'yearly' => DB::raw("{$yearFormat} as label"),
            'daily' => DB::raw("{$dayFormat} as label"),
            default => DB::raw("{$dateFormat} as label"),
        };

        $dateFilter = match ($this->period) {
            'yearly' => fn ($q) => $q,
            'daily' => fn ($q) => $q->where('created_at', '>=', now()->subDays(30)),
            default => fn ($q) => $q->where('created_at', '>=', now()->subYear()),
        };

        $salesQuery = Order::where('payment_status', PaymentStatus::Completed->value);
        $dateFilter($salesQuery);
        $salesData = (clone $salesQuery)
            ->select($labelExpr, DB::raw('COALESCE(SUM(total), 0) as total'))
            ->groupBy('label')
            ->orderBy('label')
            ->get();

        $salesLabels = $salesData->pluck('label')->toArray();
        $salesValues = $salesData->pluck('total')->map(fn ($v) => (float) $v)->toArray();

        $orderStatuses = Order::select('delivery_status', DB::raw('COUNT(*) as total'))
            ->groupBy('delivery_status')
            ->pluck('total', 'delivery_status');

        $paymentMethods = Order::select('payment_method', DB::raw('COUNT(*) as total'))
            ->whereNotNull('payment_method')
            ->where('payment_method', '!=', '')
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method');

        $monthQuery = Order::select(DB::raw("{$dateFormat} as label"), DB::raw('COUNT(*) as total'))
            ->where('created_at', '>=', now()->subYear());
        $monthOrders = (clone $monthQuery)->groupBy('label')->orderBy('label')->get();
        $monthOrderLabels = $monthOrders->pluck('label')->toArray();
        $monthOrderValues = $monthOrders->pluck('total')->map(fn ($v) => (int) $v)->toArray();

        $topBooks = OrderItem::select('book_id', DB::raw('SUM(qty) as total_qty'), DB::raw('COALESCE(SUM(subtotal), 0) as total_revenue'))
            ->with('book:id,title,slug,cover_image')
            ->groupBy('book_id')
            ->orderBy('total_qty', 'desc')
            ->take(5)
            ->get();

        $currentStart = match ($this->period) {
            'yearly' => now()->subYears(1),
            'daily' => now()->subDays(30),
            default => now()->subMonths(12),
        };

        $prevStart = match ($this->period) {
            'yearly' => now()->subYears(2),
            'daily' => now()->subDays(60),
            default => now()->subMonths(24),
        };

        $currentQuery = Order::where('payment_status', PaymentStatus::Completed->value)
            ->where('created_at', '>=', $currentStart);
        $prevQuery = Order::where('payment_status', PaymentStatus::Completed->value)
            ->where('created_at', '>=', $prevStart)
            ->where('created_at', '<', $currentStart);

        $currentRevenue = (clone $currentQuery)->sum('total');
        $prevRevenue = (clone $prevQuery)->sum('total');
        $currentOrders = (clone $currentQuery)->count();
        $prevOrders = (clone $prevQuery)->count();

        $revenueGrowth = $prevRevenue > 0 ? round((($currentRevenue - $prevRevenue) / $prevRevenue) * 100, 1) : 0;
        $ordersGrowth = $prevOrders > 0 ? round((($currentOrders - $prevOrders) / $prevOrders) * 100, 1) : 0;

        $totals = [
            'total_revenue' => Order::where('payment_status', PaymentStatus::Completed->value)->sum('total'),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('delivery_status', DeliveryStatus::Pending->value)->count(),
            'total_books' => Book::count(),
            'total_users' => User::count(),
            'total_reviews' => Review::count(),
            'avg_order_value' => Order::where('payment_status', PaymentStatus::Completed->value)->avg('total') ?? 0,
            'revenue_growth' => $revenueGrowth,
            'orders_growth' => $ordersGrowth,
        ];

        $recentOrders = Order::withCount('items')->latest()->take(10)->get();

        return view('livewire.admin.reports', compact(
            'salesLabels', 'salesValues',
            'totals', 'recentOrders',
            'orderStatuses', 'paymentMethods',
            'monthOrderLabels', 'monthOrderValues',
            'topBooks',
        ))->layout('layouts.admin');
    }
}
