@props(['active' => false])
@php
$classes = ($active ?? false)
            ? 'block px-3 py-2 text-sm font-medium uppercase tracking-wider text-black bg-neutral-100'
            : 'block px-3 py-2 text-sm font-medium uppercase tracking-wider text-neutral-600 hover:text-black hover:bg-neutral-100 transition-colors';
@endphp
<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
