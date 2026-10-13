<div>
    @section('header', 'Book Fair Editions')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold tracking-tight">Book Fair Editions</h2>
        <button wire:click="create" class="btn-primary btn-sm">+ New Edition</button>
    </div>

    @if(session('message'))
        <div class="border border-black bg-neutral-50 px-4 py-3 mb-6 text-sm font-medium">{{ session('message') }}</div>
    @endif

    <div class="mb-4">
        <input wire:model.live="search" type="text" placeholder="Search editions..." class="input-field">
    </div>

    <div class="border border-neutral-200 bg-white overflow-hidden">
        <table class="w-full">
            <thead class="bg-neutral-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Dates</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Venue</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider">Current</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse($editions as $edition)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium">{{ $edition->title }}</td>
                        <td class="px-4 py-3 text-sm text-neutral-600">
                            {{ $edition->start_date?->format('M d, Y') ?? '—' }}
                            @if($edition->end_date) – {{ $edition->end_date->format('M d, Y') }} @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-neutral-600">{{ $edition->venue ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-center">
                            @if($edition->is_current)
                                <span class="inline-block w-2 h-2 rounded-full bg-green-500"></span>
                            @else
                                <span class="inline-block w-2 h-2 rounded-full bg-neutral-300"></span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="text-xs font-semibold uppercase tracking-wider {{ $edition->status === 'published' ? 'text-brand-600' : 'text-neutral-400' }}">{{ ucfirst($edition->status) }}</span>
                        </td>
                        <td class="px-4 py-3 text-right text-sm space-x-3">
                            <button wire:click="edit({{ $edition->id }})" class="text-xs font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">Edit</button>
                            <button wire:click="delete({{ $edition->id }})" wire:confirm="Are you sure?" class="text-xs font-semibold uppercase tracking-wider text-brand-600 hover:text-brand-700 transition-colors">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-neutral-500">No book fair editions found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $editions->links() }}</div>

    {{-- Modal --}}
    @if($isOpen)
    <div class="fixed inset-0 bg-black/60 z-50 flex items-start justify-center pt-10 overflow-y-auto">
        <div class="bg-white border border-black w-full max-w-lg mx-4 p-8">
            <h3 class="text-lg font-bold tracking-tight mb-6">{{ $edition_id ? 'Edit Edition' : 'New Edition' }}</h3>

            <form wire:submit="store" class="space-y-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Title</label>
                    <input wire:model="title" class="input-field">
                    @error('title') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Tagline</label>
                    <input wire:model="tagline" class="input-field">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Start Date</label>
                        <input wire:model="start_date" type="date" class="input-field">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">End Date</label>
                        <input wire:model="end_date" type="date" class="input-field">
                        @error('end_date') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Venue</label>
                    <input wire:model="venue" class="input-field">
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Image</label>
                    <input wire:model="image" type="file" accept="image/*" class="w-full text-sm">
                    @if($existing_image && !$image)
                        <p class="text-xs text-neutral-500 mt-1">Current: {{ $existing_image }}</p>
                    @endif
                </div>

                <div>
                    <label class="flex items-center gap-2 text-sm">
                        <input wire:model="is_current" type="checkbox" class="border-neutral-300 text-black focus:ring-black">
                        <span>Set as Current Edition</span>
                    </label>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Status</label>
                    <select wire:model="status" class="input-field">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-neutral-200">
                    <button type="button" wire:click="$set('isOpen', false)" class="btn-outline btn-sm">Cancel</button>
                    <button type="submit" class="btn-primary btn-sm">{{ $edition_id ? 'Update' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
