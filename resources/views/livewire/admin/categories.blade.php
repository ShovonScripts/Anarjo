<div>
    @section('header', 'Categories')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold tracking-tight">Categories</h2>
        <button wire:click="create" class="btn-primary btn-sm">+ Add Category</button>
    </div>

    @if(session('message'))
        <div class="border border-black bg-neutral-50 px-4 py-3 mb-6 text-sm font-medium">{{ session('message') }}</div>
    @endif

    <div class="mb-4">
        <input wire:model.live="search" type="text" placeholder="Search categories..." class="input-field">
    </div>

    <div class="border border-neutral-200 bg-white overflow-hidden">
        <table class="w-full">
            <thead class="bg-neutral-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Parent</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Slug</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse($categories as $cat)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium">{{ $cat->name }}</td>
                        <td class="px-4 py-3 text-sm text-neutral-600">{{ $cat->parent?->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-neutral-500">{{ $cat->slug }}</td>
                        <td class="px-4 py-3 text-right text-sm space-x-3">
                            <button wire:click="edit({{ $cat->id }})" class="text-xs font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">Edit</button>
                            <button wire:click="delete({{ $cat->id }})" wire:confirm="Are you sure?" class="text-xs font-semibold uppercase tracking-wider text-brand-600 hover:text-brand-700 transition-colors">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-neutral-500">No categories found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $categories->links() }}</div>

    @if($isOpen)
    <div class="fixed inset-0 bg-black/60 z-50 flex items-start justify-center pt-10 overflow-y-auto">
        <div class="bg-white border border-black w-full max-w-md mx-4 p-8">
            <h3 class="text-lg font-bold tracking-tight mb-6">{{ $category_id ? 'Edit Category' : 'Add Category' }}</h3>
            <form wire:submit="store" class="space-y-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Name</label>
                    <input wire:model="name" class="input-field">
                    @error('name') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Parent Category</label>
                    <select wire:model="parent_id" class="input-field">
                        <option value="">None (Top Level)</option>
                        @foreach($allCategories as $cat)
                            @if($cat->id !== $category_id)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Image</label>
                    @if($image)
                        <div class="mb-2">
                            <img src="{{ $image->temporaryUrl() }}" class="h-24 w-full object-cover border border-neutral-200">
                            <button type="button" wire:click="$set('image', null)" class="text-xs text-brand-600 mt-1 block">Remove</button>
                        </div>
                    @elseif($existing_image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $existing_image) }}" class="h-24 w-full object-cover border border-neutral-200">
                            <button type="button" wire:click="$set('existing_image', null)" class="text-xs text-brand-600 mt-1 block">Remove</button>
                        </div>
                    @endif
                    <input type="file" wire:model="image" accept="image/*" class="block w-full text-sm text-neutral-500 file:mr-4 file:py-2 file:px-4 file:border file:border-neutral-300 file:text-sm file:font-semibold file:bg-white file:text-black hover:file:bg-neutral-50">
                    @error('image') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-neutral-200">
                    <button type="button" wire:click="$set('isOpen', false)" class="btn-outline btn-sm">Cancel</button>
                    <button type="submit" class="btn-primary btn-sm">{{ $category_id ? 'Update' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
