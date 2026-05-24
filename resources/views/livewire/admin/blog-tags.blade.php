<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold tracking-tight">Blog Tags</h2>
        <button wire:click="create" class="btn-primary btn-sm">+ New Tag</button>
    </div>

    @if(session('message'))
        <div class="border border-black bg-neutral-50 px-4 py-3 mb-6 text-sm font-medium">{{ session('message') }}</div>
    @endif
    @if(session('error'))
        <div class="border border-brand-600 bg-red-50 px-4 py-3 mb-6 text-sm font-medium">{{ session('error') }}</div>
    @endif

    <div class="mb-4">
        <input wire:model.live="search" type="text" placeholder="Search tags..." class="input-field">
    </div>

    <div class="border border-neutral-200 bg-white overflow-hidden">
        <table class="w-full">
            <thead class="bg-neutral-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Slug</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider">Posts</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse($tags as $tag)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium">{{ $tag->name }}</td>
                        <td class="px-4 py-3 text-sm text-neutral-600">{{ $tag->slug }}</td>
                        <td class="px-4 py-3 text-sm text-center">{{ $tag->blogs_count }}</td>
                        <td class="px-4 py-3 text-right text-sm space-x-3">
                            <button wire:click="edit({{ $tag->id }})" class="text-xs font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">Edit</button>
                            <button wire:click="delete({{ $tag->id }})" wire:confirm="Are you sure?" class="text-xs font-semibold uppercase tracking-wider text-brand-600 hover:text-brand-700 transition-colors">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-neutral-500">No tags found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $tags->links() }}</div>

    @if($isOpen)
    <div class="fixed inset-0 bg-black/60 z-50 flex items-start justify-center pt-20 overflow-y-auto">
        <div class="bg-white border border-black w-full max-w-lg mx-4 p-8">
            <h3 class="text-lg font-bold tracking-tight mb-6">{{ $tag_id ? 'Edit Tag' : 'New Tag' }}</h3>
            <form wire:submit="store" class="space-y-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Name</label>
                    <input wire:model="name" class="input-field">
                    @error('name') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-neutral-200">
                    <button type="button" wire:click="$set('isOpen', false)" class="btn-outline btn-sm">Cancel</button>
                    <button type="submit" class="btn-primary btn-sm">{{ $tag_id ? 'Update' : 'Create' }}</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
