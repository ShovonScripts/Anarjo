@props(['book'])
<div class="card-hover group reveal flex flex-col h-full">
    <a href="{{ route('books.show', $book->slug) }}" class="block">
        <div class="aspect-[3/4] bg-neutral-100 flex items-center justify-center overflow-hidden relative">
            @if($book->cover_image)
                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 will-change-transform" loading="lazy" decoding="async">
            @else
                <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-neutral-100 to-neutral-200">
                    <x-icon name="book" class="w-8 h-8 text-neutral-300 mb-1" />
                    <span class="text-[9px] font-medium text-neutral-400 uppercase tracking-wider">No Cover</span>
                </div>
            @endif
            @if($book->pre_order)
                <span class="absolute top-1.5 left-1.5 bg-amber-400 text-amber-900 text-[9px] font-bold uppercase tracking-wider px-1.5 py-0.5">Pre-Order</span>
            @elseif($book->stock < 1)
                <span class="absolute top-1.5 left-1.5 bg-neutral-800/90 text-white text-[9px] font-bold uppercase tracking-wider px-1.5 py-0.5 backdrop-blur-sm">Out of Stock</span>
            @elseif($book->sale_price)
                @php $discountPercent = round((($book->price - $book->sale_price) / $book->price) * 100); @endphp
                <span class="absolute top-1.5 right-1.5 bg-brand-600 text-white text-[9px] font-bold uppercase tracking-wider px-1.5 py-0.5">{{ $discountPercent }}% OFF</span>
            @endif
        </div>
    </a>
    <div class="p-2 sm:p-2.5 flex flex-col flex-1">
        <a href="{{ route('books.show', $book->slug) }}" class="block group-hover:text-brand-600 transition-colors">
            <h3 class="font-semibold text-xs uppercase tracking-wider truncate">{{ $book->title }}</h3>
            @if($book->author)
                <p class="text-[11px] text-neutral-500 mt-0.5 truncate">{{ $book->author->name }}</p>
            @endif
            <div class="mt-1.5 flex items-center gap-1.5">
                @if($book->sale_price)
                    <span class="font-bold text-brand-600 text-sm">৳{{ number_format($book->sale_price) }}</span>
                    <span class="text-[10px] text-neutral-400 line-through">৳{{ number_format($book->price) }}</span>
                @else
                    <span class="font-bold text-sm">৳{{ number_format($book->price) }}</span>
                @endif
            </div>
        </a>
        <div class="mt-auto pt-2">
            @if($book->stock > 0 || $book->pre_order)
                <livewire:add-to-cart-button :book-id="$book->id" :book-title="$book->title" :key="'card-' . $book->id" />
            @else
                <span class="block text-center text-[10px] font-semibold uppercase tracking-wider text-neutral-400 bg-neutral-50 border border-neutral-200 px-3 py-2">Out of Stock</span>
            @endif
        </div>
    </div>
</div>
