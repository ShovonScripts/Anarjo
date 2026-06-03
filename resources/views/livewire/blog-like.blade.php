<button wire:click="toggle" class="inline-flex items-center gap-2 text-sm transition-colors {{ $liked ? 'text-red-600' : 'text-neutral-500 hover:text-red-600' }}">
    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="{{ $liked ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
    </svg>
    <span>{{ $count }}</span>
</button>
