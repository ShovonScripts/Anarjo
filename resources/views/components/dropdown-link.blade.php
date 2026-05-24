@props(['active' => false])
@php
$classes = ($active ?? false)
            ? 'block w-full px-4 py-2 text-sm text-black bg-neutral-100 text-left'
            : 'block w-full px-4 py-2 text-sm text-neutral-600 hover:text-black hover:bg-neutral-100 text-left transition-colors';
@endphp
<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
