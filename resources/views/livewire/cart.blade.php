<div>
    @if(session('message'))
        <div class="bg-neutral-100 border border-black px-4 py-3 mb-6 text-sm font-medium">{{ session('message') }}</div>
    @endif

    @if(empty($items))
        <div class="text-center py-20 border border-neutral-200">
            <p class="text-6xl mb-4 text-neutral-300">+</p>
            <h2 class="text-2xl font-bold mb-2">Your cart is empty</h2>
            <p class="text-neutral-500 mb-8">Browse our collection and add books you love.</p>
            <a href="{{ route('books.index') }}" class="btn-primary">Browse Books</a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <div class="lg:col-span-2 space-y-4">
                @foreach($items as $id => $item)
                    <div class="border border-neutral-200 p-4 flex gap-4">
                        <div class="w-20 h-28 bg-neutral-100 overflow-hidden flex-shrink-0">
                            @if($item['cover_image'])
                                <img src="{{ asset('storage/' . $item['cover_image']) }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full text-2xl text-neutral-300">+</div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <a href="{{ route('books.show', $item['slug']) }}" class="font-semibold text-sm uppercase tracking-wider hover:text-brand-600 transition-colors">{{ $item['title'] }}</a>
                            <p class="text-lg font-bold mt-1">৳{{ number_format($item['price']) }}</p>
                            <div class="flex items-center gap-3 mt-3">
                                <button wire:click="updateQty({{ $id }}, {{ $item['qty'] - 1 }})" class="w-8 h-8 border border-neutral-300 flex items-center justify-center hover:bg-black hover:text-white hover:border-black transition-colors text-sm">−</button>
                                <span class="font-medium w-8 text-center text-sm">{{ $item['qty'] }}</span>
                                <button wire:click="updateQty({{ $id }}, {{ $item['qty'] + 1 }})" class="w-8 h-8 border border-neutral-300 flex items-center justify-center hover:bg-black hover:text-white hover:border-black transition-colors text-sm">+</button>
                                <button wire:click="remove({{ $id }})" class="ml-auto text-sm font-semibold uppercase tracking-wider text-brand-600 hover:text-brand-700 transition-colors">Remove</button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="border border-neutral-200 p-6 h-fit sticky top-24">
                <h3 class="text-sm font-semibold uppercase tracking-widest mb-6">Order Summary</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-neutral-600">Subtotal ({{ $count }} items)</span>
                        <span class="font-semibold">৳{{ number_format($subtotal) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-neutral-600">Shipping</span>
                        <span class="font-semibold">{{ $shipping > 0 ? setting('currency_symbol', '৳') . number_format($shipping) : 'Free' }}</span>
                    </div>
                    <div class="border-t border-neutral-200 pt-3 flex justify-between font-bold text-lg">
                        <span>Total</span>
                        <span>{{ setting('currency_symbol', '৳') }}{{ number_format($total) }}</span>
                    </div>
                </div>
                <a href="{{ route('checkout') }}" class="btn-primary w-full mt-6 text-center">Proceed to Checkout</a>
                <button wire:click="clear" class="block mt-3 w-full text-center text-sm font-semibold uppercase tracking-wider text-brand-600 hover:text-brand-700 transition-colors">Clear Cart</button>
            </div>
        </div>
    @endif
</div>
