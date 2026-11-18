<div>
    @section('header', 'Manuscripts')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold tracking-tight">Manuscripts</h2>
    </div>
    @if(session('message'))
        <div class="border border-black bg-neutral-50 px-4 py-3 mb-6 text-sm font-medium">{{ session('message') }}</div>
    @endif
    <div class="mb-4">
        <input wire:model.live="search" type="text" placeholder="Search by name or book title..." class="input-field">
    </div>
    <div class="border border-neutral-200 bg-white overflow-hidden">
        <table class="w-full">
            <thead class="bg-neutral-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Author</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Book Title</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Genre</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse($manuscripts as $m)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-4 py-3 text-sm">{{ $m->author_name }}<br><span class="text-xs text-neutral-500">{{ $m->email }}</span></td>
                        <td class="px-4 py-3 text-sm font-medium">{{ $m->book_title }}</td>
                        <td class="px-4 py-3 text-sm text-neutral-600">{{ $m->genre ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="text-xs font-semibold uppercase tracking-wider {{ $m->status === 'approved' ? 'text-brand-600' : ($m->status === 'rejected' ? 'text-red-600' : 'text-neutral-400') }}">{{ ucfirst($m->status) }}</span>
                        </td>
                        <td class="px-4 py-3 text-right text-sm space-x-3">
                            <button wire:click="review({{ $m->id }})" class="text-xs font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">Review</button>
                            <button wire:click="delete({{ $m->id }})" wire:confirm="Are you sure?" class="text-xs font-semibold uppercase tracking-wider text-brand-600 hover:text-brand-700 transition-colors">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-neutral-500">No submissions yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $manuscripts->links() }}</div>

    @if($isOpen)
    <div class="fixed inset-0 bg-black/60 z-50 flex items-start justify-center pt-10 overflow-y-auto">
        <div class="bg-white border border-black w-full max-w-lg mx-4 p-8">
            <h3 class="text-lg font-bold tracking-tight mb-6">Review Manuscript</h3>
            <form wire:submit="updateReview" class="space-y-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Status</label>
                    <select wire:model="status" class="input-field">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Remarks</label>
                    <textarea wire:model="remarks" rows="4" class="input-field"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-neutral-200">
                    <button type="button" wire:click="$set('isOpen', false)" class="btn-outline btn-sm">Cancel</button>
                    <button type="submit" class="btn-primary btn-sm">Update Review</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
