<div>
    @section('header', 'Reports')

    @push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    @endpush

    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight">Reports</h1>
        <p class="mt-1 text-sm text-neutral-500">Sales, orders, and performance analytics.</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-5 mb-8">
        <div class="bg-white border-l-4 border-emerald-500 shadow-sm p-5">
            <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-1">Revenue</p>
            <p class="text-2xl font-extrabold tracking-tight">{{ setting('currency_symbol', '৳') }}{{ number_format($totals['total_revenue']) }}</p>
            <div class="flex items-center gap-1 mt-1">
                @if($totals['revenue_growth'] > 0)
                    <span class="text-emerald-600 text-xs font-semibold">↑ {{ $totals['revenue_growth'] }}%</span>
                    <span class="text-neutral-400 text-xs">vs prev period</span>
                @elseif($totals['revenue_growth'] < 0)
                    <span class="text-red-600 text-xs font-semibold">↓ {{ abs($totals['revenue_growth']) }}%</span>
                    <span class="text-neutral-400 text-xs">vs prev period</span>
                @else
                    <span class="text-neutral-400 text-xs">No change vs prev period</span>
                @endif
            </div>
        </div>
        <div class="bg-white border-l-4 border-blue-500 shadow-sm p-5">
            <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-1">Orders</p>
            <p class="text-2xl font-extrabold tracking-tight">{{ number_format($totals['total_orders']) }}</p>
            <div class="flex items-center gap-1 mt-1">
                @if($totals['orders_growth'] > 0)
                    <span class="text-emerald-600 text-xs font-semibold">↑ {{ $totals['orders_growth'] }}%</span>
                    <span class="text-neutral-400 text-xs">vs prev period</span>
                @elseif($totals['orders_growth'] < 0)
                    <span class="text-red-600 text-xs font-semibold">↓ {{ abs($totals['orders_growth']) }}%</span>
                    <span class="text-neutral-400 text-xs">vs prev period</span>
                @else
                    <span class="text-neutral-400 text-xs">No change vs prev period</span>
                @endif
            </div>
            @if(($totals['pending_orders'] ?? 0) > 0)
                <p class="text-xs text-amber-600 mt-1">{{ $totals['pending_orders'] }} pending</p>
            @endif
        </div>
        <div class="bg-white border-l-4 border-violet-500 shadow-sm p-5">
            <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-1">Avg Order Value</p>
            <p class="text-2xl font-extrabold tracking-tight">{{ setting('currency_symbol', '৳') }}{{ number_format($totals['avg_order_value']) }}</p>
            <p class="text-xs text-neutral-400 mt-1">Per completed order</p>
        </div>
        <div class="bg-white border-l-4 border-orange-500 shadow-sm p-5">
            <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-1">Books</p>
            <p class="text-2xl font-extrabold tracking-tight">{{ number_format($totals['total_books']) }}</p>
            <p class="text-xs text-neutral-400 mt-1">In catalog</p>
        </div>
        <div class="bg-white border-l-4 border-cyan-500 shadow-sm p-5">
            <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-1">Users</p>
            <p class="text-2xl font-extrabold tracking-tight">{{ number_format($totals['total_users']) }}</p>
            <p class="text-xs text-neutral-400 mt-1">Registered accounts</p>
        </div>
        <div class="bg-white border-l-4 border-rose-500 shadow-sm p-5">
            <p class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-1">Reviews</p>
            <p class="text-2xl font-extrabold tracking-tight">{{ number_format($totals['total_reviews']) }}</p>
            <p class="text-xs text-neutral-400 mt-1">From readers</p>
        </div>
    </div>

    {{-- Charts Row 1 --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Revenue Chart --}}
        <div class="lg:col-span-2 bg-white shadow-sm border border-neutral-200">
            <div class="flex items-center justify-between px-6 py-4 border-b border-neutral-200">
                <div>
                    <h3 class="text-sm font-bold tracking-tight">Revenue</h3>
                    <p class="text-xs text-neutral-500 mt-0.5">{{ $this->period === 'daily' ? 'Last 30 days' : ($this->period === 'yearly' ? 'All time' : 'Last 12 months') }}</p>
                </div>
                <div class="flex items-center gap-1.5">
                    <button wire:click="$set('period', 'daily')"
                            class="text-xs px-3 py-1.5 border transition-colors font-medium {{ $period === 'daily' ? 'bg-black text-white border-black' : 'border-neutral-300 text-neutral-600 hover:border-black hover:text-black' }}">
                        Daily
                    </button>
                    <button wire:click="$set('period', 'monthly')"
                            class="text-xs px-3 py-1.5 border transition-colors font-medium {{ $period === 'monthly' ? 'bg-black text-white border-black' : 'border-neutral-300 text-neutral-600 hover:border-black hover:text-black' }}">
                        Monthly
                    </button>
                    <button wire:click="$set('period', 'yearly')"
                            class="text-xs px-3 py-1.5 border transition-colors font-medium {{ $period === 'yearly' ? 'bg-black text-white border-black' : 'border-neutral-300 text-neutral-600 hover:border-black hover:text-black' }}">
                        Yearly
                    </button>
                </div>
            </div>
            <div class="p-6">
                <canvas id="revenueChart" height="200" wire:key="revenue-{{ $period }}"></canvas>
            </div>
        </div>

        {{-- Order Status Doughnut --}}
        <div class="bg-white shadow-sm border border-neutral-200">
            <div class="flex items-center justify-between px-6 py-4 border-b border-neutral-200">
                <h3 class="text-sm font-bold tracking-tight">Order Status</h3>
                <span class="text-xs text-neutral-500">{{ $totals['total_orders'] }} total</span>
            </div>
            <div class="p-6">
                <canvas id="orderStatusChart" height="200" wire:key="status-{{ $orderStatuses->count() }}"></canvas>
            </div>
        </div>
    </div>

    {{-- Charts Row 2 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- Monthly Orders Bar --}}
        <div class="bg-white shadow-sm border border-neutral-200">
            <div class="flex items-center justify-between px-6 py-4 border-b border-neutral-200">
                <h3 class="text-sm font-bold tracking-tight">Monthly Orders</h3>
                <span class="text-xs text-neutral-500">Last 12 months</span>
            </div>
            <div class="p-6">
                <canvas id="ordersChart" height="180" wire:key="orders-{{ $period }}"></canvas>
            </div>
        </div>

        {{-- Payment Methods --}}
        <div class="bg-white shadow-sm border border-neutral-200">
            <div class="flex items-center justify-between px-6 py-4 border-b border-neutral-200">
                <h3 class="text-sm font-bold tracking-tight">Payment Methods</h3>
                <span class="text-xs text-neutral-500">Distribution</span>
            </div>
            <div class="p-6">
                <canvas id="paymentChart" height="180" wire:key="payment-{{ $paymentMethods->count() }}"></canvas>
            </div>
        </div>
    </div>

    {{-- Bottom Row: Top Books + Recent Orders --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Top Selling Books --}}
        <div class="bg-white shadow-sm border border-neutral-200">
            <div class="px-6 py-4 border-b border-neutral-200">
                <h3 class="text-sm font-bold tracking-tight">Top Selling Books</h3>
                <p class="text-xs text-neutral-500 mt-0.5">Best performers by units sold</p>
            </div>
            @if($topBooks->count())
                <div class="divide-y divide-neutral-200">
                    @foreach($topBooks as $i => $item)
                        <div class="flex items-center gap-4 px-6 py-4 hover:bg-neutral-50 transition-colors">
                            <span class="text-lg font-extrabold text-neutral-300 w-6 shrink-0">{{ $i + 1 }}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold truncate">{{ $item->book?->title ?? 'Deleted Book' }}</p>
                                <p class="text-xs text-neutral-500">{{ $item->total_qty }} sold · {{ setting('currency_symbol', '৳') }}{{ number_format($item->total_revenue) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="p-6 text-sm text-neutral-500 text-center">No sales data yet.</p>
            @endif
        </div>

        {{-- Recent Orders --}}
        <div class="lg:col-span-2 bg-white shadow-sm border border-neutral-200">
            <div class="flex items-center justify-between px-6 py-4 border-b border-neutral-200">
                <h3 class="text-sm font-bold tracking-tight">Recent Orders</h3>
                <a href="{{ route('admin.orders') }}" class="text-xs font-semibold uppercase tracking-wider text-neutral-500 hover:text-black transition-colors">View All</a>
            </div>
            @if($recentOrders->count())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-neutral-50 text-left">
                                <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider">Invoice</th>
                                <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider">Items</th>
                                <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider">Payment</th>
                                <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider">Delivery</th>
                                <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider text-right">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200">
                            @php
                                $paymentColors = ['completed' => 'text-emerald-600 bg-emerald-50', 'pending' => 'text-amber-600 bg-amber-50', 'failed' => 'text-red-600 bg-red-50', 'cancelled' => 'text-neutral-600 bg-neutral-50'];
                                $deliveryColors = ['delivered' => 'text-emerald-600 bg-emerald-50', 'processing' => 'text-blue-600 bg-blue-50', 'shipped' => 'text-violet-600 bg-violet-50', 'pending' => 'text-amber-600 bg-amber-50', 'cancelled' => 'text-red-600 bg-red-50'];
                            @endphp
                            @foreach($recentOrders as $order)
                                <tr class="hover:bg-neutral-50 transition-colors">
                                    <td class="px-6 py-3">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="font-mono text-xs font-medium hover:text-brand-600 transition-colors">{{ $order->invoice_no }}</a>
                                    </td>
                                    <td class="px-6 py-3 text-neutral-600 text-xs">{{ $order->items_count }}</td>
                                    <td class="px-6 py-3 font-semibold">{{ setting('currency_symbol', '৳') }}{{ number_format($order->total) }}</td>
                                    <td class="px-6 py-3">
                                        <span class="inline-block text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded {{ $paymentColors[$order->payment_status] ?? 'text-neutral-600 bg-neutral-50' }}">{{ $order->payment_status }}</span>
                                    </td>
                                    <td class="px-6 py-3">
                                        <span class="inline-block text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded {{ $deliveryColors[$order->delivery_status] ?? 'text-neutral-600 bg-neutral-50' }}">{{ $order->delivery_status }}</span>
                                    </td>
                                    <td class="px-6 py-3 text-neutral-500 text-xs text-right">{{ $order->created_at->format('M d, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="p-8 text-center text-sm text-neutral-500">No orders yet.</p>
            @endif
        </div>
    </div>

    @push('js')
    <script>
    document.addEventListener('livewire:init', function () {
        const gridColor = 'rgba(0,0,0,0.06)';
        const textColor = '#737373';
        let charts = {};

        function destroyCharts() {
            Object.values(charts).forEach(c => { try { c.destroy(); } catch(e) {} });
            charts = {};
        }

        function initCharts() {
            destroyCharts();

            const labels = @json($salesLabels);
            const values = @json($salesValues);

            // Revenue Line Chart
            const revCtx = document.getElementById('revenueChart');
            if (revCtx && labels.length) {
                charts.revenue = new Chart(revCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Revenue',
                            data: values,
                            borderColor: '#059669',
                            backgroundColor: 'rgba(5, 150, 105, 0.08)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#059669',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            borderWidth: 2,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: ctx => '৳' + Number(ctx.raw).toLocaleString()
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { color: textColor, font: { size: 11 }, callback: v => '৳' + v.toLocaleString() },
                                grid: { color: gridColor }
                            },
                            x: {
                                ticks: { color: textColor, font: { size: 11 }, maxTicksLimit: 12 },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

            // Order Status Doughnut
            const statusCtx = document.getElementById('orderStatusChart');
            if (statusCtx) {
                const statusData = @json($orderStatuses);
                const sLabels = Object.keys(statusData);
                const sValues = Object.values(statusData);
                const sColors = { pending: '#F59E0B', processing: '#3B82F6', shipped: '#8B5CF6', delivered: '#10B981', cancelled: '#EF4444' };
                const sBg = sLabels.map(l => sColors[l] || '#737373');

                if (sLabels.length) {
                    charts.status = new Chart(statusCtx, {
                        type: 'doughnut',
                        data: {
                            labels: sLabels.map(l => l.charAt(0).toUpperCase() + l.slice(1)),
                            datasets: [{ data: sValues, backgroundColor: sBg, borderWidth: 2, borderColor: '#fff' }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '65%',
                            plugins: {
                                legend: { position: 'bottom', labels: { padding: 12, usePointStyle: true, pointStyleWidth: 8, font: { size: 11 }, color: textColor } }
                            }
                        }
                    });
                }
            }

            // Monthly Orders Bar
            const ordCtx = document.getElementById('ordersChart');
            if (ordCtx) {
                const oLabels = @json($monthOrderLabels);
                const oValues = @json($monthOrderValues);
                if (oLabels.length) {
                    charts.orders = new Chart(ordCtx, {
                        type: 'bar',
                        data: {
                            labels: oLabels,
                            datasets: [{
                                label: 'Orders',
                                data: oValues,
                                backgroundColor: 'rgba(59, 130, 246, 0.6)',
                                borderColor: '#3B82F6',
                                borderWidth: 1,
                                borderRadius: 4,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, ticks: { color: textColor, font: { size: 11 }, stepSize: 1 }, grid: { color: gridColor } },
                                x: { ticks: { color: textColor, font: { size: 11 } }, grid: { display: false } }
                            }
                        }
                    });
                }
            }

            // Payment Methods Doughnut
            const payCtx = document.getElementById('paymentChart');
            if (payCtx) {
                const payData = @json($paymentMethods);
                const pLabels = Object.keys(payData);
                const pValues = Object.values(payData);
                const pColors = ['#059669', '#3B82F6', '#8B5CF6', '#F59E0B', '#EF4444', '#06B6D4', '#F97316'];
                const pBg = pLabels.map((_, i) => pColors[i % pColors.length]);

                if (pLabels.length) {
                    charts.payment = new Chart(payCtx, {
                        type: 'doughnut',
                        data: {
                            labels: pLabels.map(l => l.charAt(0).toUpperCase() + l.slice(1)),
                            datasets: [{ data: pValues, backgroundColor: pBg, borderWidth: 2, borderColor: '#fff' }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '60%',
                            plugins: {
                                legend: { position: 'bottom', labels: { padding: 12, usePointStyle: true, pointStyleWidth: 8, font: { size: 11 }, color: textColor } }
                            }
                        }
                    });
                }
            }
        }

        // Init on first load
        initCharts();

        // Reinit after Livewire updates
        Livewire.hook('morph.updated', () => initCharts());
    });
    </script>
    @endpush
</div>
