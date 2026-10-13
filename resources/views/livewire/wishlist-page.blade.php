<div>
    @section('title', 'My Wishlist - ' . config('app.name'))

    <x-breadcrumbs :items="[['label' => 'Wishlist']]" />
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="section-heading mb-12">My Wishlist</h1>

        @if($items->count())
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($items as $item)
                    <div class="group border border-neutral-200 bg-white hover:border-black transition-colors relative">
                        <a href="{{ route('books.show', $item->book->slug) }}">
                            <div class="aspect-[3/4] bg-neutral-100 overflow-hidden flex items-center justify-center">
                                @if($item->book->cover_image)
                                    <img src="{{ asset('storage/' . $item->book->cover_image) }}" alt="{{ $item->book->title }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-4xl text-neutral-300">+</span>
                                @endif
                            </div>
                            <div class="p-4">
                                <p class="text-xs font-semibold uppercase tracking-[0.15em] text-neutral-500 mb-1">{{ $item->book->author?->name ?? 'Unknown' }}</p>
                                <h3 class="font-bold text-sm group-hover:text-brand-600 transition-colors">{{ $item->book->title }}</h3>
                                @if($item->book->sale_price)
                                    <p class="text-sm font-semibold mt-2">৳{{ number_format($item->book->sale_price) }} <span class="text-xs text-neutral-400 line-through">৳{{ number_format($item->book->price) }}</span></p>
                                @else
                                    <p class="text-sm font-semibold mt-2">৳{{ number_format($item->book->price) }}</p>
                                @endif
                            </div>
                        </a>
                        <button wire:click="remove({{ $item->id }})" class="absolute top-2 right-2 bg-white border border-neutral-200 p-1.5 md:opacity-0 md:group-hover:opacity-100 transition-opacity hover:bg-neutral-50" aria-label="Remove from wishlist">
                            <svg class="w-4 h-4 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-20 border border-neutral-200">
                <svg class="w-12 h-12 mx-auto mb-4 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <p class="text-neutral-500 mb-4">Your wishlist is empty.</p>
                <a href="{{ route('books.index') }}" class="btn-primary btn-sm">Browse Books</a>
            </div>
        @endif
    </div>
</div>
