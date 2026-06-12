<div>
    @section('header', 'Coupons')
    @if (session('message'))
        <div class="mb-6 px-5 py-4 border border-brand-600 bg-brand-50 text-brand-700 text-sm font-medium">{{ session('message') }}</div>
    @endif

    <div class="flex justify-between items-center mb-8">
        <div class="w-64">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search coupons..." class="w-full px-4 py-2.5 border border-neutral-300 text-sm focus:outline-none focus:border-black transition-colors">
        </div>
        <button wire:click="create" class="btn-primary">+ New Coupon</button>
    </div>

    <div class="border border-neutral-200 bg-white overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-neutral-50 border-b border-neutral-200 text-left">
                    <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider">Code</th>
                    <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider">Type</th>
                    <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider">Value</th>
                    <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider">Valid</th>
                    <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider">Usage</th>
                    <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse($coupons as $coupon)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-5 py-4 font-semibold uppercase tracking-wider">{{ $coupon->code }}</td>
                        <td class="px-5 py-4 text-neutral-600 uppercase text-xs">{{ $coupon->type }}</td>
                        <td class="px-5 py-4">
                            {{ $coupon->type === 'percent' ? $coupon->value . '%' : '৳' . number_format($coupon->value) }}
                        </td>
                        <td class="px-5 py-4 text-xs text-neutral-600">
                            {{ $coupon->start_date?->format('d M Y') }}
                            @if($coupon->end_date) – {{ $coupon->end_date->format('d M Y') }} @endif
                        </td>
                        <td class="px-5 py-4 text-xs text-neutral-600">
                            {{ $coupon->usage_count ?? 0 }}{{ $coupon->usage_limit ? ' / ' . $coupon->usage_limit : '' }}
                        </td>
                        <td class="px-5 py-4">
                            <span class="text-xs font-semibold uppercase tracking-wider {{ $coupon->status === 'active' ? 'text-brand-600' : 'text-neutral-400' }}">{{ $coupon->status }}</span>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <button wire:click="edit({{ $coupon->id }})" class="text-xs font-semibold uppercase tracking-wider text-neutral-600 hover:text-black mr-4 transition-colors">Edit</button>
                            <button wire:click="delete({{ $coupon->id }})" wire:confirm="Delete this coupon?" class="text-xs font-semibold uppercase tracking-wider text-brand-600 hover:text-black transition-colors">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-neutral-500">No coupons found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $coupons->links() }}
    </div>

    {{-- Modal --}}
    @if($isOpen)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center" wire:click.self="$set('isOpen', false)">
            <div class="bg-white w-full max-w-lg p-8">
                <h3 class="text-lg font-bold tracking-tight mb-6">{{ $coupon_id ? 'Edit Coupon' : 'New Coupon' }}</h3>
                <form wire:submit="store" class="space-y-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Code</label>
                        <input type="text" wire:model="code" placeholder="e.g. SUMMER20" class="w-full px-4 py-2.5 border border-neutral-300 text-sm focus:outline-none focus:border-black transition-colors uppercase">
                        @error('code') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Type</label>
                            <select wire:model="type" class="w-full px-4 py-2.5 border border-neutral-300 text-sm focus:outline-none focus:border-black transition-colors">
                                <option value="fixed">Fixed (৳)</option>
                                <option value="percent">Percent (%)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Value</label>
                            <input type="number" step="0.01" wire:model="value" class="w-full px-4 py-2.5 border border-neutral-300 text-sm focus:outline-none focus:border-black transition-colors">
                            @error('value') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Minimum Order Amount</label>
                        <input type="number" step="0.01" wire:model="min_amount" class="w-full px-4 py-2.5 border border-neutral-300 text-sm focus:outline-none focus:border-black transition-colors">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Start Date</label>
                            <input type="date" wire:model="start_date" class="w-full px-4 py-2.5 border border-neutral-300 text-sm focus:outline-none focus:border-black transition-colors">
                            @error('start_date') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-widest mb-2">End Date</label>
                            <input type="date" wire:model="end_date" class="w-full px-4 py-2.5 border border-neutral-300 text-sm focus:outline-none focus:border-black transition-colors">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Usage Limit</label>
                            <input type="number" wire:model="usage_limit" placeholder="Unlimited" class="w-full px-4 py-2.5 border border-neutral-300 text-sm focus:outline-none focus:border-black transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Status</label>
                            <select wire:model="status" class="w-full px-4 py-2.5 border border-neutral-300 text-sm focus:outline-none focus:border-black transition-colors">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-4 border-t border-neutral-200">
                        <button type="button" wire:click="$set('isOpen', false)" class="px-5 py-2.5 text-sm font-semibold text-neutral-600 hover:text-black transition-colors">Cancel</button>
                        <button type="submit" class="px-5 py-2.5 text-sm font-semibold bg-black text-white hover:bg-neutral-800 transition-colors">{{ $coupon_id ? 'Update' : 'Create' }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
