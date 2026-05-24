@props(['href' => '#', 'label' => '', 'active' => false])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-2.5 text-sm font-medium text-white bg-brand-600'
            : 'flex items-center px-4 py-2.5 text-sm font-medium text-neutral-400 hover:text-white hover:bg-neutral-800 transition-colors';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $label }}
</a>
