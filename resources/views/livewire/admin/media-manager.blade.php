<div>
    @section('header', 'Media Library')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold tracking-tight">Media Library</h2>
    </div>

    @if(session('message'))
        <div class="border border-black bg-neutral-50 px-4 py-3 mb-6 text-sm font-medium">{{ session('message') }}</div>
    @endif

    {{-- Upload Area --}}
    <div class="border-2 border-dashed border-neutral-300 bg-white p-8 mb-8 text-center hover:border-black transition-colors">
        <form wire:submit="upload">
            <svg class="w-10 h-10 mx-auto mb-3 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
            <p class="text-sm font-medium text-neutral-600 mb-1">Drag & drop files or click to browse</p>
            <p class="text-xs text-neutral-400 mb-4">JPG, PNG, GIF, WebP, PDF, DOC, XLS, ZIP — max 10MB each</p>
            <input type="file" wire:model="upload" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.zip" class="block w-full text-sm text-neutral-500 file:mr-4 file:py-2 file:px-4 file:border file:border-neutral-300 file:text-sm file:font-semibold file:bg-white file:text-black hover:file:bg-neutral-50 cursor-pointer">
            @error('upload.*') <span class="text-xs text-brand-600 mt-2 block">{{ $message }}</span> @enderror
            @if($upload)
                <div class="mt-4 flex items-center justify-center gap-3">
                    <span class="text-sm text-neutral-500">{{ count($upload) }} file(s) selected</span>
                    <button type="submit" class="btn-primary btn-sm" wire:loading.attr="disabled">
                        <span wire:loading.remove>Upload</span>
                        <span wire:loading>Uploading...</span>
                    </button>
                </div>
            @endif
        </form>
    </div>

    {{-- Filters --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <input wire:model.live="search" type="text" placeholder="Search files..." class="input-field">
        <select wire:model.live="typeFilter" class="input-field">
            <option value="">All Types</option>
            <option value="image">Images</option>
            <option value="document">Documents</option>
        </select>
    </div>

    {{-- Grid --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @forelse($allMedia as $media)
            <div class="group border border-neutral-200 bg-white hover:border-black transition-colors relative">
                <div class="aspect-square overflow-hidden bg-neutral-100 flex items-center justify-center">
                    @if($media->isImage())
                        <img src="{{ $media->url() }}" alt="{{ $media->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="text-center p-4">
                            <svg class="w-10 h-10 mx-auto text-neutral-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            <p class="text-xs text-neutral-500 truncate">{{ strtoupper(pathinfo($media->name, PATHINFO_EXTENSION)) }}</p>
                        </div>
                    @endif
                </div>
                <div class="p-2">
                    <p class="text-xs font-medium truncate" title="{{ $media->name }}">{{ $media->name }}</p>
                    <p class="text-xs text-neutral-400">{{ $media->sizeForHumans() }}</p>
                </div>
                {{-- Hover actions --}}
                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                    <button type="button" x-data x-on:click="navigator.clipboard.writeText('{{ $media->url() }}'); $dispatch('notify', { message: 'URL copied!' })" class="bg-white text-black text-xs font-semibold px-3 py-1.5 hover:bg-neutral-100 transition-colors">Copy URL</button>
                    <button wire:click="delete({{ $media->id }})" wire:confirm="Delete this file?" class="bg-brand-600 text-white text-xs font-semibold px-3 py-1.5 hover:bg-brand-700 transition-colors">Delete</button>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16 text-neutral-500">
                <svg class="w-12 h-12 mx-auto mb-3 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p class="text-sm">No media found.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $allMedia->links() }}</div>
</div>
