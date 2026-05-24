@props(['value'])
<label {{ $attributes->merge(['class' => 'block text-sm font-semibold uppercase tracking-wider mb-1']) }}>
    {{ $value ?? $slot }}
</label>
