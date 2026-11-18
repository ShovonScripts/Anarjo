<div>
    @section('header', 'Brands')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold tracking-tight">Brands</h2>
        <button wire:click="create" class="btn-primary btn-sm">+ Add Brand</button>
    </div>

    @if(session('message'))
        <div class="border border-black bg-neutral-50 px-4 py-3 mb-6 text-sm font-medium">{{ session('message') }}</div>
    @endif

    <div class="mb-4">
        <input wire:model.live="search" type="text" placeholder="Search brands..." class="input-field">
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
                @forelse($brands as $brand)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium">{{ $brand->name }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="text-xs font-semibold uppercase tracking-wider {{ $brand->status === 'active' ? 'text-brand-600' : 'text-neutral-400' }}">
                                {{ ucfirst($brand->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right text-sm space-x-3">
                            <button wire:click="edit({{ $brand->id }})" class="text-xs font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">Edit</button>
                            <button wire:click="delete({{ $brand->id }})" wire:confirm="Are you sure?" class="text-xs font-semibold uppercase tracking-wider text-brand-600 hover:text-brand-700 transition-colors">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="px-4 py-8 text-center text-neutral-500">No brands found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $brands->links() }}</div>

    @if($isOpen)
    <div class="fixed inset-0 bg-black/60 z-50 flex items-start justify-center pt-10 overflow-y-auto">
        <div class="bg-white border border-black w-full max-w-lg mx-4 p-8">
            <h3 class="text-lg font-bold tracking-tight mb-6">{{ $brand_id ? 'Edit Brand' : 'Add Brand' }}</h3>
            <form wire:submit="store" class="space-y-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Name</label>
                    <input wire:model="name" class="input-field">
                    @error('name') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Description</label>
                    <textarea wire:model="description" rows="3" class="input-field"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Logo</label>
                    @if($logo)
                        <div class="mb-2">
                            <img src="{{ $logo->temporaryUrl() }}" class="h-20 w-20 object-cover border border-neutral-200">
                            <button type="button" wire:click="$set('logo', null)" class="text-xs text-brand-600 mt-1 block">Remove</button>
                        </div>
                    @elseif($existing_logo)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $existing_logo) }}" class="h-20 w-20 object-cover border border-neutral-200">
                            <button type="button" wire:click="$set('existing_logo', null)" class="text-xs text-brand-600 mt-1 block">Remove</button>
                        </div>
                    @endif
                    <input type="file" wire:model="logo" accept="image/*" class="block w-full text-sm text-neutral-500 file:mr-4 file:py-2 file:px-4 file:border file:border-neutral-300 file:text-sm file:font-semibold file:bg-white file:text-black hover:file:bg-neutral-50">
                    @error('logo') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
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
                    <button type="submit" class="btn-primary btn-sm">{{ $brand_id ? 'Update' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
