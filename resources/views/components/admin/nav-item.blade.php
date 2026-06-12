@props(['href' => '#', 'label' => '', 'active' => false])

@php
$classes = ($active ?? false)
            ? 'flex items-center gap-3 px-4 py-2 text-sm font-medium text-white bg-white/[0.07] border-l-2 border-brand-500'
            : 'flex items-center gap-3 px-4 py-2 text-sm font-medium text-neutral-400 hover:text-white hover:bg-white/[0.04] border-l-2 border-transparent transition-all duration-150';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
    <span>{{ $label }}</span>
</a>
