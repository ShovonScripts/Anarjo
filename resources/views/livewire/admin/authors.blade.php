<div>
    @section('header', 'Authors')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold tracking-tight">Authors</h2>
        <button wire:click="create" class="btn-primary btn-sm">+ Add Author</button>
    </div>

    @if(session('message'))
        <div class="border border-black bg-neutral-50 px-4 py-3 mb-6 text-sm font-medium">{{ session('message') }}</div>
    @endif

    <div class="mb-4">
        <input wire:model.live="search" type="text" placeholder="Search authors..." class="input-field">
    </div>

    <div class="border border-neutral-200 bg-white overflow-hidden">
        <table class="w-full">
            <thead class="bg-neutral-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse($authors as $author)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium">{{ $author->name }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="text-xs font-semibold uppercase tracking-wider {{ $author->status === 'active' ? 'text-brand-600' : 'text-neutral-400' }}">
                                {{ ucfirst($author->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right text-sm space-x-3">
                            <button wire:click="edit({{ $author->id }})" class="text-xs font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">Edit</button>
                            <button wire:click="delete({{ $author->id }})" wire:confirm="Are you sure?" class="text-xs font-semibold uppercase tracking-wider text-brand-600 hover:text-brand-700 transition-colors">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-8 text-center text-neutral-500">No authors found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $authors->links() }}</div>

    @if($isOpen)
    <div class="fixed inset-0 bg-black/60 z-50 flex items-start justify-center pt-10 overflow-y-auto">
        <div class="bg-white border border-black w-full max-w-lg mx-4 p-8">
            <h3 class="text-lg font-bold tracking-tight mb-6">{{ $author_id ? 'Edit Author' : 'Add Author' }}</h3>
            <form wire:submit="store" class="space-y-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Name</label>
                    <input wire:model="name" class="input-field">
                    @error('name') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Bio</label>
                    <textarea wire:model="bio" rows="3" class="input-field"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Photo</label>
                    @if($photo)
                        <div class="mb-2">
                            <img src="{{ $photo->temporaryUrl() }}" class="h-24 w-24 object-cover border border-neutral-200 rounded-full">
                            <button type="button" wire:click="$set('photo', null)" class="text-xs text-brand-600 mt-1 block">Remove</button>
                        </div>
                    @elseif($existing_photo)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $existing_photo) }}" class="h-24 w-24 object-cover border border-neutral-200 rounded-full">
                            <button type="button" wire:click="$set('existing_photo', null)" class="text-xs text-brand-600 mt-1 block">Remove</button>
                        </div>
                    @endif
                    <input type="file" wire:model="photo" accept="image/*" class="block w-full text-sm text-neutral-500 file:mr-4 file:py-2 file:px-4 file:border file:border-neutral-300 file:text-sm file:font-semibold file:bg-white file:text-black hover:file:bg-neutral-50">
                    @error('photo') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Status</label>
                    <select wire:model="status" class="input-field">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-neutral-200">
                    <button type="button" wire:click="$set('isOpen', false)" class="btn-outline btn-sm">Cancel</button>
                    <button type="submit" class="btn-primary btn-sm">{{ $author_id ? 'Update' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
