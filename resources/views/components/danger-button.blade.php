@props(['disabled' => false])
<button {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'btn-red']) !!}>
    {{ $slot }}
</button>
