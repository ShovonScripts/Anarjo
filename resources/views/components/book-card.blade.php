@props(['book'])
<div class="card-hover group">
    <a href="{{ route('books.show', $book->slug) }}">
        <div class="aspect-[3/4] bg-neutral-100 flex items-center justify-center overflow-hidden">
            @if($book->cover_image)
                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @else
                <span class="text-4xl text-neutral-300">+</span>
            @endif
        </div>
        <div class="p-4">
            <h3 class="font-semibold text-sm uppercase tracking-wider truncate">{{ $book->title }}</h3>
            @if($book->author)
                <p class="text-xs text-neutral-500 mt-1">{{ $book->author->name }}</p>
            @endif
            <div class="mt-3 flex items-center gap-2">
                @if($book->sale_price)
                    <span class="font-bold text-brand-600">৳{{ number_format($book->sale_price) }}</span>
                    <span class="text-xs text-neutral-400 line-through">৳{{ number_format($book->price) }}</span>
                @else
                    <span class="font-bold">৳{{ number_format($book->price) }}</span>
                @endif
            </div>
            <div class="mt-3">
                <livewire:add-to-cart-button :book-id="$book->id" :book-title="$book->title" :key="'card-' . $book->id" />
            </div>
        </div>
    </a>
</div>
