@props(['disabled' => false, 'type' => 'button'])
<button {{ $disabled ? 'disabled' : '' }} type="{{ $type }}" {{ $attributes->merge(['class' => 'btn-outline']) }}>
    {{ $slot }}
</button>
