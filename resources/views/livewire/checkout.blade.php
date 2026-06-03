<div>
    @if(session('error'))
        <div class="bg-neutral-100 border border-brand-600 px-4 py-3 mb-6 text-sm font-medium">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        {{-- Address Form --}}
        <div class="lg:col-span-2">
            <div class="border border-neutral-200 p-8">
                <h2 class="text-lg font-bold uppercase tracking-wider mb-8">Shipping Address</h2>
                <form wire:submit="placeOrder" class="space-y-5">
                    <div class="grid grid-cols-2 gap-5">
                        @guest
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Email Address</label>
                            <input type="email" wire:model="email" class="input-field" placeholder="For order confirmation">
                            @error('email') <span class="text-xs text-brand-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        @endguest
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Full Name</label>
                            <input wire:model="name" class="input-field">
                            @error('name') <span class="text-xs text-brand-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Phone</label>
                            <input wire:model="phone" class="input-field">
                            @error('phone') <span class="text-xs text-brand-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-widest mb-2">District</label>
                            <input wire:model.live="district" class="input-field">
                            @error('district') <span class="text-xs text-brand-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Area</label>
                            <input wire:model="area" class="input-field">
                            @error('area') <span class="text-xs text-brand-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Address</label>
                            <textarea wire:model="address" rows="2" class="input-field"></textarea>
                            @error('address') <span class="text-xs text-brand-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Postal Code</label>
                            <input wire:model="postal_code" class="input-field">
                        </div>
                    </div>

                    <div class="pt-6 border-t border-neutral-200">
                        <h3 class="text-xs font-semibold uppercase tracking-widest mb-4">Payment Method</h3>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($paymentMethods as $pm)
                                <label class="border border-neutral-200 p-4 flex items-center gap-3 cursor-pointer hover:border-black transition-colors">
                                    <input type="radio" wire:model="payment_method" value="{{ $pm['id'] }}" class="text-black focus:ring-black">
                                    <span class="text-sm font-medium">{{ $pm['label'] }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('payment_method') <span class="text-xs text-brand-600 mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Order Notes (optional)</label>
                        <textarea wire:model="notes" rows="2" class="input-field"></textarea>
                    </div>

                    <button type="submit" class="btn-primary w-full text-center">
                        Place Order — {{ setting('currency_symbol', '৳') }}{{ number_format($total) }}
                    </button>
                </form>
            </div>
        </div>

        {{-- Order Summary --}}
        <div>
            <div class="border border-neutral-200 p-6 sticky top-24">
                <h3 class="text-sm font-semibold uppercase tracking-widest mb-6">Order Summary</h3>
                <div class="space-y-4">
                    @foreach($items as $item)
                        <div class="flex gap-4">
                            <div class="w-12 h-16 bg-neutral-100 flex-shrink-0 flex items-center justify-center text-sm">
                                @if($item['cover_image'])
                                    <img src="{{ asset('storage/' . $item['cover_image']) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-neutral-300">+</span>
                                @endif
                            </div>
                            <div class="flex-1 text-sm">
                                <p class="font-semibold text-xs uppercase tracking-wider truncate">{{ $item['title'] }}</p>
                                <p class="text-neutral-500 text-xs mt-1">Qty: {{ $item['qty'] }}</p>
                                <p class="font-bold mt-1">{{ setting('currency_symbol', '৳') }}{{ number_format($item['price'] * $item['qty']) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{-- Coupon --}}
                <div class="border-t border-neutral-200 pt-4 mt-4">
                    @if($appliedCoupon)
                        <div class="flex justify-between items-center text-sm mb-2">
                            <span class="font-semibold text-brand-600 uppercase">{{ $appliedCoupon->code }}</span>
                            <button wire:click="removeCoupon" class="text-xs text-brand-600 hover:text-black">Remove</button>
                        </div>
                    @else
                        <form wire:submit="applyCoupon" class="flex gap-2">
                            <input type="text" wire:model="coupon_code" placeholder="Coupon code" class="flex-1 px-3 py-2 border border-neutral-300 text-sm focus:outline-none focus:border-black transition-colors uppercase">
                            <button type="submit" class="px-4 py-2 text-sm font-semibold bg-black text-white hover:bg-neutral-800 transition-colors">Apply</button>
                        </form>
                        @error('coupon_code') <span class="text-xs text-brand-600 mt-1 block">{{ $message }}</span> @enderror
                    @endif
                </div>
                <div class="border-t border-neutral-200 pt-4 mt-4 space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-neutral-600">Subtotal</span><span class="font-semibold">{{ setting('currency_symbol', '৳') }}{{ number_format($subtotal) }}</span></div>
                    <div class="flex justify-between"><span class="text-neutral-600">Shipping</span><span class="font-semibold">{{ $shipping > 0 ? setting('currency_symbol', '৳') . number_format($shipping) : 'Free' }}</span></div>
                    @if($discount > 0)
                        <div class="flex justify-between text-brand-600"><span>Discount</span><span>–{{ setting('currency_symbol', '৳') }}{{ number_format($discount) }}</span></div>
                    @endif
                    <div class="border-t border-neutral-200 pt-2 flex justify-between font-bold text-lg">
                        <span>Total</span>
                        <span>{{ setting('currency_symbol', '৳') }}{{ number_format($total) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
