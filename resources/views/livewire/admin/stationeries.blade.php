<div>
    @section('header', 'Stationeries')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold tracking-tight">Stationeries</h2>
        <button wire:click="create" class="btn-primary btn-sm">+ Add Item</button>
    </div>

    @if(session('message'))
        <div class="border border-black bg-neutral-50 px-4 py-3 mb-6 text-sm font-medium">{{ session('message') }}</div>
    @endif

    <div class="mb-4">
        <input wire:model.live="search" type="text" placeholder="Search stationeries..." class="input-field">
    </div>

    <div class="border border-neutral-200 bg-white overflow-hidden">
        <table class="w-full">
            <thead class="bg-neutral-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Brand</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Price</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Stock</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse($items as $item)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-4 py-3 text-sm">{{ $item->title }}</td>
                        <td class="px-4 py-3 text-sm text-neutral-600">{{ $item->brand?->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm font-semibold">৳{{ number_format($item->sale_price ?? $item->price) }}</td>
                        <td class="px-4 py-3 text-sm">{{ $item->stock }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="text-xs font-semibold uppercase tracking-wider {{ $item->status === 'published' ? 'text-brand-600' : ($item->status === 'draft' ? 'text-neutral-400' : 'text-neutral-500') }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right text-sm space-x-3">
                            <button wire:click="edit({{ $item->id }})" class="text-xs font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">Edit</button>
                            <button wire:click="delete({{ $item->id }})" wire:confirm="Are you sure?" class="text-xs font-semibold uppercase tracking-wider text-brand-600 hover:text-brand-700 transition-colors">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-neutral-500">No stationeries found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $items->links() }}</div>

    {{-- Modal --}}
    @if($isOpen)
    <div class="fixed inset-0 bg-black/60 z-50 flex items-start justify-center pt-10 overflow-y-auto">
        <div class="bg-white border border-black w-full max-w-xl mx-4 p-8">
            <h3 class="text-lg font-bold tracking-tight mb-6">{{ $stationery_id ? 'Edit Stationery' : 'Add Stationery' }}</h3>

            <form wire:submit="store" class="space-y-5">
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Title</label>
                        <input wire:model="title" class="input-field">
                        @error('title') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Brand</label>
                        <select wire:model="brand_id" class="input-field">
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Price</label>
                        <input wire:model="price" type="number" step="0.01" class="input-field">
                        @error('price') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Sale Price</label>
                        <input wire:model="sale_price" type="number" step="0.01" class="input-field">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Stock</label>
                        <input wire:model="stock" type="number" class="input-field">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Description</label>
                    <textarea wire:model="description" rows="3" class="input-field"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Image</label>
                    <input wire:model="new_image" type="file" accept="image/*" class="w-full text-sm">
                    @if($image && !$new_image)
                        <p class="text-xs text-neutral-500 mt-1">Current: {{ $image }}</p>
                    @endif
                </div>

                <div class="flex flex-wrap gap-6">
                    <label class="flex items-center gap-2 text-sm">
                        <input wire:model="featured" type="checkbox" class="border-neutral-300 text-black focus:ring-black">
                        <span>Featured</span>
                    </label>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Status</label>
                    <select wire:model="status" class="input-field">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-neutral-200">
                    <button type="button" wire:click="$set('isOpen', false)" class="btn-outline btn-sm">Cancel</button>
                    <button type="submit" class="btn-primary btn-sm">{{ $stationery_id ? 'Update' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
