@php
    $isAvailable = $preOrder || $stock > 0;
    $stockLabel = '';
    if ($preOrder) {
        $stockLabel = 'Pre-order';
    } elseif ($stock <= 0) {
        $stockLabel = 'Out of stock';
    } elseif ($stock <= 5) {
        $stockLabel = 'Only ' . $stock . ' left';
    }
@endphp

<div>
    @if($justAdded)
        <div class="flex items-center gap-2">
            <a href="{{ route('cart') }}" class="w-full {{ $size === 'lg' ? 'btn-primary text-sm py-3 px-6 tracking-wide' : 'inline-flex items-center justify-center bg-green-700 hover:bg-green-800 text-white text-[11px] sm:text-xs font-semibold uppercase tracking-tight sm:tracking-normal py-2 px-2.5 transition-all duration-200' }} uppercase justify-center whitespace-nowrap">
                <svg class="{{ $size === 'lg' ? 'w-4 h-4 mr-2' : 'w-3.5 h-3.5 mr-1.5 flex-shrink-0' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                View Cart
            </a>
        </div>
    @else
        @if(! $isAvailable)
            <button disabled class="w-full {{ $size === 'lg' ? 'text-sm py-3 px-6 tracking-wide' : 'text-[11px] sm:text-xs py-2 px-2.5 tracking-tight sm:tracking-normal' }} uppercase inline-flex items-center justify-center bg-neutral-200 text-neutral-500 font-semibold cursor-not-allowed whitespace-nowrap">
                Out of Stock
            </button>
        @else
            <div class="space-y-1.5">
                <button wire:click="add" wire:loading.attr="disabled" class="w-full {{ $size === 'lg' ? 'btn-primary text-sm py-3 px-6 tracking-wide' : 'inline-flex items-center justify-center bg-neutral-900 text-white hover:bg-brand-600 text-[11px] sm:text-xs font-semibold uppercase tracking-tight sm:tracking-normal py-2 px-2.5 transition-all duration-200' }} uppercase disabled:opacity-50 whitespace-nowrap">
                    <span wire:loading.remove wire:target="add" class="inline-flex items-center {{ $size === 'lg' ? 'gap-2' : 'gap-1.5' }}">
                        <svg class="{{ $size === 'lg' ? 'w-4 h-4' : 'w-3.5 h-3.5 flex-shrink-0' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/></svg>
                        Add to Cart
                    </span>
                    <span wire:loading wire:target="add" class="inline-flex items-center {{ $size === 'lg' ? 'gap-2' : 'gap-1.5' }}">
                        <svg class="animate-spin {{ $size === 'lg' ? 'w-4 h-4' : 'w-3.5 h-3.5 flex-shrink-0' }}" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        Adding…
                    </span>
                </button>
                @if($stockLabel)
                    <p class="text-[10px] sm:text-xs {{ $stock <= 5 && $stock > 0 ? 'text-amber-600' : 'text-neutral-500' }} text-center font-medium">{{ $stockLabel }}</p>
                @endif
            </div>
        @endif
    @endif
</div>
