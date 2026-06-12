<div>
    @section('header', 'Order #' . $order->id)
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.orders') }}" wire:navigate class="text-sm font-semibold uppercase tracking-wider text-neutral-500 hover:text-black transition-colors">&larr; Back to Orders</a>
        <span class="text-xs text-neutral-400">{{ $order->created_at->format('M d, Y \a\t h:i A') }}</span>
    </div>

    @if(session('message'))
        <div class="border border-black bg-neutral-50 px-4 py-3 mb-6 text-sm font-medium">{{ session('message') }}</div>
    @endif

    <div class="grid grid-cols-3 gap-6">
        {{-- Left: Order Items --}}
        <div class="col-span-2 space-y-6">
            <div class="border border-neutral-200 bg-white">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h3 class="text-sm font-bold tracking-tight">Order Items</h3>
                </div>
                <table class="w-full">
                    <thead class="bg-neutral-100">
                        <tr>
                            <th class="px-6 py-2.5 text-left text-xs font-semibold uppercase tracking-wider">Book</th>
                            <th class="px-6 py-2.5 text-right text-xs font-semibold uppercase tracking-wider">Price</th>
                            <th class="px-6 py-2.5 text-right text-xs font-semibold uppercase tracking-wider">Qty</th>
                            <th class="px-6 py-2.5 text-right text-xs font-semibold uppercase tracking-wider">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200">
                        @foreach($order->items as $item)
                            <tr class="hover:bg-neutral-50 transition-colors">
                                <td class="px-6 py-3 text-sm font-medium">{{ $item->book?->title ?? 'Deleted Book' }}</td>
                                <td class="px-6 py-3 text-sm text-right">৳{{ number_format($item->price) }}</td>
                                <td class="px-6 py-3 text-sm text-right">{{ $item->qty }}</td>
                                <td class="px-6 py-3 text-sm text-right font-semibold">৳{{ number_format($item->subtotal) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-neutral-50">
                        <tr><td colspan="3" class="px-6 py-2.5 text-sm text-right text-neutral-600">Subtotal</td><td class="px-6 py-2.5 text-sm text-right font-semibold">৳{{ number_format($order->subtotal) }}</td></tr>
                        <tr><td colspan="3" class="px-6 py-2.5 text-sm text-right text-neutral-600">Shipping</td><td class="px-6 py-2.5 text-sm text-right font-semibold">৳{{ number_format($order->shipping) }}</td></tr>
                        @if($order->discount > 0)
                            <tr><td colspan="3" class="px-6 py-2.5 text-sm text-right text-green-600">Discount</td><td class="px-6 py-2.5 text-sm text-right font-semibold text-green-600">-৳{{ number_format($order->discount) }}</td></tr>
                        @endif
                        <tr class="border-t border-neutral-300"><td colspan="3" class="px-6 py-3 text-sm text-right font-bold">Total</td><td class="px-6 py-3 text-sm text-right font-bold">৳{{ number_format($order->total) }}</td></tr>
                    </tfoot>
                </table>
            </div>

            {{-- Shipping Address --}}
            @if($order->address)
                <div class="border border-neutral-200 bg-white p-6">
                    <h3 class="text-sm font-bold tracking-tight mb-3">Shipping Address</h3>
                    <p class="text-sm text-neutral-600 leading-relaxed">
                        {{ $order->address->name }}<br>
                        {{ $order->address->phone }}<br>
                        {{ $order->address->address }}, {{ $order->address->area }}<br>
                        {{ $order->address->district }} - {{ $order->address->postal_code }}
                    </p>
                </div>
            @endif
        </div>

        {{-- Right: Status Controls --}}
        <div class="space-y-6">
            <div class="border border-neutral-200 bg-white p-6">
                <h3 class="text-sm font-bold tracking-tight mb-4">Order Info</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between"><span class="text-neutral-500">Invoice</span><span class="font-mono font-semibold">{{ $order->invoice_no }}</span></div>
                    <div class="flex justify-between"><span class="text-neutral-500">Customer</span><span>{{ $order->user?->name ?? 'Guest' }}</span></div>
                    <div class="flex justify-between"><span class="text-neutral-500">Email</span><span class="text-xs">{{ $order->user?->email ?? '—' }}</span></div>
                    <div class="flex justify-between"><span class="text-neutral-500">Phone</span><span>{{ $order->address?->phone ?? '—' }}</span></div>
                    <div class="flex justify-between"><span class="text-neutral-500">Payment</span><span class="uppercase text-xs font-semibold">{{ $order->payment_method ?? '—' }}</span></div>
                    @if($order->coupon)
                        <div class="flex justify-between"><span class="text-neutral-500">Coupon</span><span class="font-mono text-xs">{{ $order->coupon->code }}</span></div>
                    @endif
                </dl>
            </div>

            <div class="border border-neutral-200 bg-white p-6">
                <h3 class="text-sm font-bold tracking-tight mb-4">Update Status</h3>
                <form wire:submit="updateStatus" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Payment</label>
                        <select wire:model="payment_status" class="input-field">
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Delivery</label>
                        <select wire:model="delivery_status" class="input-field">
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Notes</label>
                        <textarea wire:model="notes" rows="3" class="input-field" placeholder="Admin notes..."></textarea>
                    </div>
                    <button type="submit" class="btn-primary btn-sm w-full" wire:loading.attr="disabled">
                        <span wire:loading.remove>Update Status</span>
                        <span wire:loading>Saving...</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
