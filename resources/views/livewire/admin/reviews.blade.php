<div>
    @section('header', 'Reviews')
    @if (session('message'))
        <div class="mb-6 px-5 py-4 border border-brand-600 bg-brand-50 text-brand-700 text-sm font-medium">{{ session('message') }}</div>
    @endif

    <div class="flex justify-between items-center mb-8">
        <div class="flex gap-3">
            <select wire:model.live="filter" class="px-4 py-2.5 border border-neutral-300 text-sm focus:outline-none focus:border-black">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search book or user..." class="w-64 px-4 py-2.5 border border-neutral-300 text-sm focus:outline-none focus:border-black">
        </div>
        <span class="text-sm text-neutral-500">{{ $reviews->total() }} reviews</span>
    </div>

    <div class="border border-neutral-200 bg-white overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-neutral-50 border-b border-neutral-200 text-left">
                    <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider">Book</th>
                    <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider">User</th>
                    <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider">Rating</th>
                    <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider">Review</th>
                    <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse($reviews as $review)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-5 py-4">
                            <a href="{{ route('books.show', $review->book->slug) }}" target="_blank" class="font-semibold hover:text-brand-600 transition-colors">{{ $review->book->title }}</a>
                        </td>
                        <td class="px-5 py-4 text-neutral-600">{{ $review->user?->name ?? 'Deleted' }}</td>
                        <td class="px-5 py-4">
                            <div class="flex gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-black' : 'text-neutral-300' }}" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                @endfor
                            </div>
                        </td>
                        <td class="px-5 py-4 text-neutral-600 max-w-xs truncate">{{ Str::limit($review->review, 80) }}</td>
                        <td class="px-5 py-4">
                            <span class="text-xs font-semibold uppercase tracking-wider
                                {{ $review->status === 'approved' ? 'text-brand-600' : '' }}
                                {{ $review->status === 'pending' ? 'text-neutral-500' : '' }}
                                {{ $review->status === 'rejected' ? 'text-red-600' : '' }}">
                                {{ $review->status }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right whitespace-nowrap">
                            @if($review->status === 'pending')
                                <button wire:click="approve({{ $review->id }})" class="text-xs font-semibold uppercase tracking-wider text-brand-600 hover:text-black mr-3 transition-colors">Approve</button>
                                <button wire:click="reject({{ $review->id }})" class="text-xs font-semibold uppercase tracking-wider text-red-600 hover:text-black mr-3 transition-colors">Reject</button>
                            @endif
                            <button wire:click="delete({{ $review->id }})" wire:confirm="Delete this review?" class="text-xs font-semibold uppercase tracking-wider text-neutral-600 hover:text-black transition-colors">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-neutral-500">No reviews found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $reviews->links() }}
    </div>
</div>
