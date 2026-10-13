<div>
    <x-breadcrumbs :items="[['label' => 'Cart', 'url' => route('cart')], ['label' => 'Checkout']]" />

    {{-- Order Progress --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-4">
        <div class="flex items-center gap-2 md:gap-4 text-xs font-semibold uppercase tracking-widest">
            <span class="flex items-center gap-2 text-brand-600">
                <span class="w-6 h-6 rounded-full bg-brand-600 text-white flex items-center justify-center text-[10px] font-bold">1</span>
                Cart
            </span>
            <span class="flex-1 h-px bg-brand-600"></span>
            <span class="flex items-center gap-2 text-brand-600">
                <span class="w-6 h-6 rounded-full bg-brand-600 text-white flex items-center justify-center text-[10px] font-bold">2</span>
                Checkout
            </span>
            <span class="flex-1 h-px bg-neutral-200"></span>
            <span class="flex items-center gap-2 text-neutral-400">
                <span class="w-6 h-6 rounded-full border-2 border-neutral-300 flex items-center justify-center text-[10px] font-bold">3</span>
                Confirmation
            </span>
        </div>
    </div>

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm font-medium flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    <form wire:submit="placeOrder" class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        {{-- Address Form --}}
        <div class="lg:col-span-2">
            <div class="border border-neutral-200 p-6 md:p-8 bg-white">
                <h2 class="text-lg font-bold uppercase tracking-wider mb-8">Shipping Address</h2>
                <div class="space-y-5">
                    <div class="grid grid-cols-2 gap-5">
                        @guest
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Email Address</label>
                            <input type="email" wire:model="email" class="input-field" placeholder="For order confirmation">
                            @error('email') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        @endguest
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Full Name</label>
                            <input wire:model="name" class="input-field" placeholder="Full name as per ID">
                            @error('name') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Phone</label>
                            <input wire:model="phone" class="input-field" placeholder="01XXXXXXXXX">
                            @error('phone') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-widest mb-2">District</label>
                            <input wire:model.live="district" class="input-field" placeholder="e.g. Dhaka, Chattogram">
                            @error('district') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Area</label>
                            <input wire:model="area" class="input-field" placeholder="e.g. Dhanmondi, Banani">
                            @error('area') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Full Address</label>
                            <textarea wire:model="address" rows="2" class="input-field" placeholder="House #, Road #, Building, Floor, Flat..."></textarea>
                            @error('address') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Postal Code</label>
                            <input wire:model="postal_code" class="input-field" placeholder="e.g. 1205">
                        </div>
                    </div>

                    {{-- Shipping Cost Live Update --}}
                    @if($district)
                        <div class="bg-neutral-50 border border-neutral-200 px-4 py-3 text-sm flex items-center gap-3">
                            <svg class="w-4 h-4 text-neutral-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 17l4 4 4-4m-4-5v9M20.88 18.09A5 5 0 0018 9h-1.26A8 8 0 103 16.29"/></svg>
                            <span class="text-neutral-600">
                                Shipping to <span class="font-semibold">{{ $district }}</span>:
                                @if($shipping > 0)
                                    <span class="font-semibold">{{ $currency }}{{ number_format($shipping) }}</span>
                                @else
                                    <span class="font-semibold text-black">Free</span> — You qualify for free shipping!
                                @endif
                            </span>
                        </div>
                    @endif

                    <div class="pt-6 border-t border-neutral-200">
                        <h3 class="text-xs font-semibold uppercase tracking-widest mb-4">Payment Method</h3>
                        <div class="space-y-3">
                            @foreach($paymentMethods as $pm)
                                <label class="border {{ $payment_method === $pm['id'] ? 'border-black bg-neutral-50' : 'border-neutral-200' }} p-4 flex items-center gap-4 cursor-pointer hover:border-black transition-all duration-200 group">
                                    <input type="radio" wire:model="payment_method" value="{{ $pm['id'] }}" class="text-black focus:ring-black w-4 h-4">
                                    <div class="flex-1">
                                        <span class="text-sm font-semibold">{{ $pm['label'] }}</span>
                                        <p class="text-xs text-neutral-500 mt-0.5">{{ $pm['description'] }}</p>
                                    </div>
                                    @if($pm['icon'] === 'cash')
                                        <svg class="w-6 h-6 text-neutral-400 group-hover:text-black transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    @elseif($pm['icon'] === 'bkash')
                                        <svg class="w-6 h-6 text-neutral-400 group-hover:text-[#E2136E] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    @else
                                        <svg class="w-6 h-6 text-neutral-400 group-hover:text-black transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                        @error('payment_method') <span class="text-xs text-red-600 mt-2 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Order Notes (optional)</label>
                        <textarea wire:model="notes" rows="2" class="input-field" placeholder="Any special delivery instructions..."></textarea>
                    </div>
                </div>

                @error('cart')
                    <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 mt-6 flex items-center gap-3">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                        {{ $message }}
                    </div>
                @enderror

                {{-- Mobile: Show order summary + submit inline --}}
                <div class="lg:hidden mt-8 pt-6 border-t border-neutral-200">
                    <div class="space-y-3 text-sm mb-6">
                        <div class="flex justify-between"><span class="text-neutral-600">Subtotal</span><span class="font-semibold">{{ $currency }}{{ number_format($subtotal) }}</span></div>
                        <div class="flex justify-between"><span class="text-neutral-600">Shipping</span><span class="font-semibold">{{ $shipping > 0 ? $currency . number_format($shipping) : 'Free' }}</span></div>
                        @if($discount > 0)
                            <div class="flex justify-between text-green-600"><span>Discount</span><span>-{{ $currency }}{{ number_format($discount) }}</span></div>
                        @endif
                        <div class="border-t border-neutral-200 pt-2 flex justify-between font-bold text-lg">
                            <span>Total</span>
                            <span>{{ $currency }}{{ number_format($total) }}</span>
                        </div>
                    </div>
                    <button type="submit" wire:loading.attr="disabled" class="btn-primary w-full text-center">
                        <span wire:loading.remove wire:target="placeOrder">Place Order — {{ $currency }}{{ number_format($total) }}</span>
                        <span wire:loading wire:target="placeOrder" class="inline-flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            Placing your order…
                        </span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Order Summary Sidebar --}}
        <div class="hidden lg:block">
            <div class="border border-neutral-200 p-6 sticky top-24 bg-white">
                <h3 class="text-sm font-semibold uppercase tracking-widest mb-6">Order Summary</h3>
                <div class="space-y-4">
                    @foreach($items as $item)
                        <div class="flex gap-3">
                            <div class="w-12 h-16 bg-neutral-100 flex-shrink-0 flex items-center justify-center text-sm overflow-hidden">
                                @if($item['image'])
                                    <img src="{{ asset('storage/' . $item['image']) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-neutral-300">+</span>
                                @endif
                            </div>
                            <div class="flex-1 text-sm min-w-0">
                                <p class="font-semibold text-xs uppercase tracking-wider truncate">{{ $item['title'] }}</p>
                                <p class="text-neutral-500 text-xs mt-1">Qty: {{ $item['qty'] }}</p>
                                <p class="font-bold mt-1">{{ $currency }}{{ number_format($item['price'] * $item['qty']) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Coupon --}}
                <div class="border-t border-neutral-200 pt-4 mt-4">
                    @if($appliedCoupon)
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span class="font-semibold text-green-700 text-sm uppercase">{{ $appliedCoupon->code }}</span>
                            </div>
                            <button wire:click="removeCoupon" type="button" class="text-xs text-red-600 hover:text-red-700 font-semibold transition-colors">Remove</button>
                        </div>
                    @else
                        <div class="flex gap-2">
                            <input type="text" wire:model="coupon_code" placeholder="Coupon code" class="flex-1 px-3 py-2 border border-neutral-300 text-sm focus:outline-none focus:border-black transition-colors uppercase">
                            <button type="button" wire:click="applyCoupon" wire:loading.attr="disabled" class="px-4 py-2 text-sm font-semibold bg-black text-white hover:bg-neutral-800 transition-colors disabled:opacity-50">
                                <span wire:loading.remove wire:target="applyCoupon">Apply</span>
                                <span wire:loading wire:target="applyCoupon">
                                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                </span>
                            </button>
                        </div>
                        @error('coupon_code') <span class="text-xs text-red-600 mt-2 block">{{ $message }}</span> @enderror
                    @endif
                </div>

                <div class="border-t border-neutral-200 pt-4 mt-4 space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-neutral-600">Subtotal</span><span class="font-semibold">{{ $currency }}{{ number_format($subtotal) }}</span></div>
                    <div class="flex justify-between">
                        <span class="text-neutral-600">Shipping</span>
                        <span class="font-semibold">{{ $shipping > 0 ? $currency . number_format($shipping) : 'Free' }}</span>
                    </div>
                    @if($discount > 0)
                        <div class="flex justify-between text-green-600"><span>Discount</span><span>-{{ $currency }}{{ number_format($discount) }}</span></div>
                    @endif
                    <div class="border-t border-neutral-200 pt-2 flex justify-between font-bold text-lg">
                        <span>Total</span>
                        <span>{{ $currency }}{{ number_format($total) }}</span>
                    </div>
                </div>

                <button type="submit" wire:loading.attr="disabled" class="btn-primary w-full text-center mt-6">
                    <span wire:loading.remove wire:target="placeOrder">Place Order — {{ $currency }}{{ number_format($total) }}</span>
                    <span wire:loading wire:target="placeOrder" class="inline-flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        Placing your order…
                    </span>
                </button>

                <p class="text-xs text-neutral-400 text-center mt-4">
                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Secure checkout. Your data is protected.
                </p>
            </div>
        </div>
    </form>
    </div>
</div>
