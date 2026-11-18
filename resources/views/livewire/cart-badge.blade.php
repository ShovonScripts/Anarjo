<div wire:loading.class="opacity-50"
     x-data="{ bouncing: false }"
     @cart-updated.window="bouncing = true; setTimeout(() => bouncing = false, 400)">
    <a href="{{ route('cart') }}" class="relative inline-flex items-center" aria-label="Shopping cart">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="square" stroke-linejoin="miter" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
        </svg>
        @if($count > 0)
            <span class="absolute -top-2 -right-2 bg-brand-600 text-white text-xs font-bold w-5 h-5 flex items-center justify-center transition-transform duration-300"
                  :class="{ 'scale-125': bouncing }">
                {{ $count }}
            </span>
        @endif
    </a>
</div>
