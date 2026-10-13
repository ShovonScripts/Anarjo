<div>
    @section('header', 'Hero Slider')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold tracking-tight">Hero Slider</h2>
        <button wire:click="create" class="btn-primary btn-sm">+ Add Slide</button>
    </div>

    @if(session('message'))
        <div class="border border-black bg-neutral-50 px-4 py-3 mb-6 text-sm font-medium">{{ session('message') }}</div>
    @endif

    <div class="border border-neutral-200 bg-white overflow-hidden">
        <table class="w-full">
            <thead class="bg-neutral-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Image</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Link</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Order</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse($slides as $slide)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-4 py-3">
                            @if($slide->image)
                                <img src="{{ asset('storage/' . $slide->image) }}" class="w-20 h-12 object-cover border border-neutral-200">
                            @else
                                <span class="text-xs text-neutral-400">No image</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm max-w-[200px] truncate font-medium">{{ $slide->title ?: '—' }}</td>
                        <td class="px-4 py-3 text-sm text-neutral-500 max-w-[200px] truncate">{{ $slide->link ?: '—' }}</td>
                        <td class="px-4 py-3 text-sm">{{ $slide->sort_order }}</td>
                        <td class="px-4 py-3">
                            <button wire:click="toggleStatus({{ $slide->id }})" class="text-xs font-semibold uppercase tracking-wider {{ $slide->status ? 'text-brand-600' : 'text-neutral-400' }}">
                                {{ $slide->status ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td class="px-4 py-3 text-right text-sm space-x-3">
                            <button wire:click="edit({{ $slide->id }})" class="text-xs font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">Edit</button>
                            <button wire:click="delete({{ $slide->id }})" wire:confirm="Are you sure?" class="text-xs font-semibold uppercase tracking-wider text-brand-600 hover:text-brand-700 transition-colors">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-neutral-500">No slides yet. Add one!</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $slides->links() }}</div>

    {{-- Modal --}}
    @if($isOpen)
    <div class="fixed inset-0 bg-black/60 z-50 flex items-start justify-center pt-10 overflow-y-auto">
        <div class="bg-white border border-black w-full max-w-xl mx-4 p-8">
            <h3 class="text-lg font-bold tracking-tight mb-6">{{ $slide_id ? 'Edit Slide' : 'Add Slide' }}</h3>

            <form wire:submit="store" class="space-y-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Image</label>
                    <input wire:model="new_image" type="file" accept="image/*" class="w-full text-sm">
                    @if($image && !$new_image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $image) }}" class="h-40 object-cover border border-neutral-200">
                        </div>
                    @endif
                    @error('new_image') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Title (displayed on hero)</label>
                    <input wire:model="title" class="input-field" placeholder="e.g. Discover Your Next Read">
                    @error('title') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Subtitle</label>
                    <input wire:model="subtitle" class="input-field" placeholder="A short supporting line">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Description</label>
                    <textarea wire:model="description" rows="2" class="input-field" placeholder="Optional description text"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Link (optional)</label>
                    <input wire:model="link" class="input-field" placeholder="https://... or /books">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Link Button Text</label>
                    <input wire:model="link_text" class="input-field" placeholder="e.g. Shop Now, Learn More">
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Sort Order</label>
                        <input wire:model="sort_order" type="number" min="0" class="input-field">
                        @error('sort_order') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-end pb-2">
                        <label class="flex items-center gap-2 text-sm">
                            <input wire:model="status" type="checkbox" class="border-neutral-300 text-black focus:ring-black">
                            <span>Active</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-neutral-200">
                    <button type="button" wire:click="$set('isOpen', false)" class="btn-outline btn-sm">Cancel</button>
                    <button type="submit" class="btn-primary btn-sm">{{ $slide_id ? 'Update' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
