@props(['active' => false])
@php
$classes = ($active ?? false)
            ? 'nav-link nav-link-active'
            : 'nav-link';
@endphp
<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
