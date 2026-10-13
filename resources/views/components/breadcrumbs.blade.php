@props(['items' => []])

<nav aria-label="Breadcrumb" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
    <ol class="flex flex-wrap items-center gap-1.5 text-xs font-semibold uppercase tracking-widest text-neutral-400">
        <li>
            <a href="{{ route('home') }}" class="hover:text-black transition-colors">Home</a>
        </li>
        @foreach($items as $item)
            <li class="flex items-center gap-1.5">
                <x-icon name="chevron-right" class="w-3 h-3" />
                @if(isset($item['url']))
                    <a href="{{ $item['url'] }}" class="hover:text-black transition-colors">{{ $item['label'] }}</a>
                @else
                    <span class="text-black" aria-current="page">{{ $item['label'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
