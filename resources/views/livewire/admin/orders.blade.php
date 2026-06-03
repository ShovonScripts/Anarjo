<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold tracking-tight">Orders</h2>
    </div>

    @if(session('message'))
        <div class="border border-black bg-neutral-50 px-4 py-3 mb-6 text-sm font-medium">{{ session('message') }}</div>
    @endif

    {{-- Filters --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <input wire:model.live="search" type="text" placeholder="Search by invoice or customer..." class="input-field">
        <select wire:model.live="paymentFilter" class="input-field">
            <option value="">All Payments</option>
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
            <option value="failed">Failed</option>
        </select>
        <select wire:model.live="deliveryFilter" class="input-field">
            <option value="">All Deliveries</option>
            <option value="pending">Pending</option>
            <option value="processing">Processing</option>
            <option value="shipped">Shipped</option>
            <option value="delivered">Delivered</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="border border-neutral-200 bg-white overflow-hidden">
        <table class="w-full">
            <thead class="bg-neutral-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Invoice</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Customer</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Total</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Payment</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Delivery</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Date</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse($orders as $order)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-mono font-medium">{{ $order->invoice_no }}</td>
                        <td class="px-4 py-3 text-sm">{{ $order->user?->name ?? 'Guest' }}</td>
                        <td class="px-4 py-3 text-sm font-semibold">৳{{ number_format($order->total) }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="text-xs font-semibold uppercase tracking-wider
                                {{ $order->payment_status === 'completed' ? 'text-brand-600' : ($order->payment_status === 'failed' || $order->payment_status === 'cancelled' ? 'text-red-600' : 'text-neutral-400') }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="text-xs font-semibold uppercase tracking-wider
                                {{ $order->delivery_status === 'delivered' ? 'text-brand-600' : ($order->delivery_status === 'cancelled' ? 'text-red-600' : 'text-neutral-400') }}">
                                {{ ucfirst($order->delivery_status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-500">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-right text-sm">
                            <a href="{{ route('admin.orders.show', $order) }}" wire:navigate class="text-xs font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-neutral-500">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $orders->links() }}</div>
</div>
