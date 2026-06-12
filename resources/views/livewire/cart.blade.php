<div>
    @section('title', 'Shopping Cart - ' . config('app.name'))

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

        {{-- Page Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-12">
            <div>
                <h1 class="section-heading">Shopping Cart</h1>
                @if(!empty($items))
                    <p class="text-neutral-500 text-sm mt-2">
                        {{ $count }} {{ $count === 1 ? 'item' : 'items' }} in your cart
                    </p>
                @endif
            </div>
            <a href="{{ route('books.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold uppercase tracking-wider text-neutral-500 hover:text-black transition-colors group">
                <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M19 12H5m7-7l-7 7 7 7"/></svg>
                Continue Shopping
            </a>
        </div>

        @if(empty($items))

            {{-- Empty State --}}
            <div class="text-center py-24 border border-neutral-200">
                <svg class="w-20 h-20 mx-auto mb-6 text-neutral-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                </svg>
                <h2 class="text-2xl font-bold mb-3">Your cart is empty</h2>
                <p class="text-neutral-500 mb-8 max-w-md mx-auto">Looks like you haven't added anything yet. Browse our collection and discover your next great read.</p>
                <a href="{{ route('books.index') }}" class="btn-primary">Browse Books</a>
            </div>

        @else

            {{-- Shipping Progress --}}
            @if($freeThreshold > 0)
                <div class="mb-12">
                    @if($subtotal < $freeThreshold)
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-sm text-neutral-600">
                                Add <span class="font-semibold text-black">{{ setting('currency_symbol', '৳') }}{{ number_format($freeThreshold - $subtotal) }}</span> more for
                                <span class="font-semibold text-black uppercase tracking-wider">Free Shipping</span>
                            </p>
                            <span class="text-xs font-semibold text-neutral-400">{{ number_format($shippingProgress, 0) }}%</span>
                        </div>
                        <div class="w-full h-2 bg-neutral-200">
                            <div class="h-full bg-black transition-all duration-500" style="width: {{ $shippingProgress }}%"></div>
                        </div>
                    @else
                        <div class="bg-neutral-50 border border-neutral-200 px-6 py-4 flex items-center gap-3">
                            <svg class="w-5 h-5 text-black flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <p class="text-sm font-semibold uppercase tracking-wider">You've qualified for FREE shipping!</p>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Cart Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

                {{-- Cart Items --}}
                <div class="lg:col-span-2 space-y-4">
                    @foreach($items as $id => $item)
                        <div class="border border-neutral-200 p-5 md:p-6 flex flex-col sm:flex-row gap-5 sm:gap-6 hover:border-black transition-colors duration-200 group/card">

                            {{-- Image --}}
                            <a href="{{ route('books.show', $item['slug']) }}" class="w-full sm:w-24 lg:w-28 flex-shrink-0">
                                <div class="aspect-[3/4] bg-neutral-100 overflow-hidden flex items-center justify-center">
                                    @if($item['cover_image'])
                                        <img src="{{ asset('storage/' . $item['cover_image']) }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover group-hover/card:scale-105 transition-transform duration-500">
                                    @else
                                        <span class="text-2xl text-neutral-300">+</span>
                                    @endif
                                </div>
                            </a>

                            {{-- Details --}}
                            <div class="flex-1 min-w-0 flex flex-col">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <a href="{{ route('books.show', $item['slug']) }}" class="font-semibold text-sm uppercase tracking-wider hover:text-brand-600 transition-colors line-clamp-2">
                                            {{ $item['title'] }}
                                        </a>
                                        <p class="text-xs text-neutral-500 mt-1 uppercase tracking-wider">
                                            Unit Price: {{ setting('currency_symbol', '৳') }}{{ number_format($item['price']) }}
                                        </p>
                                    </div>
                                    <p class="hidden sm:block font-bold text-lg flex-shrink-0">
                                        {{ setting('currency_symbol', '৳') }}{{ number_format($item['price'] * $item['qty']) }}
                                    </p>
                                </div>

                                <div class="flex items-center justify-between mt-auto pt-4">
                                    <div class="flex items-center border border-neutral-300">
                                        <button wire:click="updateQty({{ $id }}, {{ $item['qty'] - 1 }})"
                                                class="w-10 h-10 flex items-center justify-center text-sm hover:bg-black hover:text-white transition-colors"
                                                @if($item['qty'] <= 1) wire:click="remove({{ $id }})" @endif>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M20 12H4"/></svg>
                                        </button>
                                        <span class="w-10 h-10 flex items-center justify-center text-sm font-medium border-x border-neutral-300">{{ $item['qty'] }}</span>
                                        <button wire:click="updateQty({{ $id }}, {{ $item['qty'] + 1 }})"
                                                class="w-10 h-10 flex items-center justify-center text-sm hover:bg-black hover:text-white transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        </button>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span class="sm:hidden font-bold text-base">
                                            {{ setting('currency_symbol', '৳') }}{{ number_format($item['price'] * $item['qty']) }}
                                        </span>
                                        <button wire:click="remove({{ $id }})" class="text-xs font-semibold uppercase tracking-wider text-neutral-400 hover:text-brand-600 transition-colors">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Order Summary --}}
                <div>
                    <div class="border border-neutral-200 p-6 md:p-8 sticky top-24">
                        <h3 class="text-sm font-semibold uppercase tracking-widest mb-8">Order Summary</h3>

                        <div class="space-y-4 text-sm">
                            <div class="flex justify-between">
                                <span class="text-neutral-600">Subtotal ({{ $count }} {{ $count === 1 ? 'item' : 'items' }})</span>
                                <span class="font-semibold">{{ setting('currency_symbol', '৳') }}{{ number_format($subtotal) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-neutral-600">Shipping</span>
                                <span class="font-semibold">
                                    @if($shipping > 0)
                                        {{ setting('currency_symbol', '৳') }}{{ number_format($shipping) }}
                                    @else
                                        <span class="text-black">Free</span>
                                    @endif
                                </span>
                            </div>
                            @if($shipping > 0 && $freeThreshold > 0)
                                <div class="bg-neutral-50 px-4 py-3 text-xs text-neutral-600">
                                    Free shipping on orders over {{ setting('currency_symbol', '৳') }}{{ number_format($freeThreshold) }}
                                </div>
                            @endif
                            <div class="border-t border-neutral-200 pt-4 flex justify-between font-bold text-xl">
                                <span>Total</span>
                                <span>{{ setting('currency_symbol', '৳') }}{{ number_format($total) }}</span>
                            </div>
                        </div>

                        <a href="{{ route('checkout') }}" class="btn-primary w-full text-center mt-8">Proceed to Checkout</a>

                        <button wire:click="clear" class="block w-full text-center mt-4 text-xs font-semibold uppercase tracking-wider text-neutral-400 hover:text-brand-600 transition-colors">
                            Clear Cart
                        </button>
                    </div>
                </div>

            </div>

            {{-- You Might Also Like --}}
            @if($relatedBooks->count())
                <div class="mt-20 pt-16 border-t border-neutral-200">
                    <div class="flex items-center justify-between mb-10">
                        <h2 class="section-heading">You Might Also Like</h2>
                        <a href="{{ route('books.index') }}" class="text-sm font-semibold uppercase tracking-wider text-neutral-500 hover:text-black transition-colors">
                            View All
                        </a>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        @foreach($relatedBooks as $book)
                            <x-book-card :book="$book" :key="'related-' . $book->id" />
                        @endforeach
                    </div>
                </div>
            @endif

        @endif
    </div>
</div>