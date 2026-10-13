@props(['item'])
<div class="card-hover group reveal flex flex-col h-full">
    <a href="{{ route('stationeries.show', $item->slug) }}" class="block">
        <div class="aspect-[1/1] bg-neutral-100 flex items-center justify-center overflow-hidden relative">
            @if($item->image)
                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 will-change-transform" loading="lazy" decoding="async">
            @else
                <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-neutral-100 to-neutral-200">
                    <x-icon name="cog" class="w-10 h-10 text-neutral-300 mb-2" />
                    <span class="text-[10px] font-medium text-neutral-400 uppercase tracking-wider">No Image</span>
                </div>
            @endif
            @if($item->stock < 1)
                <span class="absolute top-2 left-2 bg-neutral-800/90 text-white text-[10px] font-bold uppercase tracking-wider px-2 py-1 backdrop-blur-sm">Out of Stock</span>
            @elseif($item->sale_price)
                @php $discountPercent = round((($item->price - $item->sale_price) / $item->price) * 100); @endphp
                <span class="absolute top-2 right-2 bg-brand-600 text-white text-[10px] font-bold uppercase tracking-wider px-2 py-1">{{ $discountPercent }}% OFF</span>
            @endif
        </div>
    </a>
    <div class="p-4 flex flex-col flex-1">
        <a href="{{ route('stationeries.show', $item->slug) }}" class="block group-hover:text-brand-600 transition-colors">
            <h3 class="font-semibold text-sm uppercase tracking-wider truncate">{{ $item->title }}</h3>
            @if($item->brand)
                <p class="text-xs text-neutral-500 mt-1">{{ $item->brand->name }}</p>
            @endif
            <div class="mt-3 flex items-center gap-2">
                @if($item->sale_price)
                    <span class="font-bold text-brand-600">৳{{ number_format($item->sale_price) }}</span>
                    <span class="text-xs text-neutral-400 line-through">৳{{ number_format($item->price) }}</span>
                @else
                    <span class="font-bold">৳{{ number_format($item->price) }}</span>
                @endif
            </div>
        </a>
        <div class="mt-auto pt-3">
            @if($item->stock > 0)
                <livewire:add-to-cart-button :book-id="$item->id" :book-title="$item->title" type="stationery" :key="'stat-' . $item->id" />
            @else
                <span class="block text-center text-xs font-semibold uppercase tracking-wider text-neutral-400 bg-neutral-50 border border-neutral-200 px-4 py-3">Out of Stock</span>
            @endif
        </div>
    </div>
</div>
