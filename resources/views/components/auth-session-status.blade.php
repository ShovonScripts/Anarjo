@props(['status'])
@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-black bg-neutral-100 border border-black px-4 py-3']) }}>
        {{ $status }}
    </div>
@endif
