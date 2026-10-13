<div>
    @section('header', 'Comments')
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
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search commenter or target..." class="w-64 px-4 py-2.5 border border-neutral-300 text-sm focus:outline-none focus:border-black">
        </div>
        <span class="text-sm text-neutral-500">{{ $comments->total() }} comments</span>
    </div>

    <div class="border border-neutral-200 bg-white overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-neutral-50 border-b border-neutral-200 text-left">
                    <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider">User</th>
                    <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider">On</th>
                    <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider">Comment</th>
                    <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse($comments as $comment)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-5 py-4 font-semibold">{{ $comment->user?->name ?? 'Deleted' }}</td>
                        <td class="px-5 py-4 text-neutral-600 max-w-[180px] truncate">
                            {{ class_basename($comment->commentable_type) }}
                            @if($comment->commentable)
                                - {{ Str::limit($comment->commentable->title ?? $comment->commentable->name, 40) }}
                            @endif
                        </td>
                        <td class="px-5 py-4 text-neutral-600 max-w-xs truncate">{{ Str::limit($comment->body, 80) }}</td>
                        <td class="px-5 py-4">
                            <span class="text-xs font-semibold uppercase tracking-wider
                                {{ $comment->status === 'approved' ? 'text-brand-600' : '' }}
                                {{ $comment->status === 'pending' ? 'text-neutral-500' : '' }}
                                {{ $comment->status === 'rejected' ? 'text-red-600' : '' }}">
                                {{ $comment->status }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-right whitespace-nowrap">
                            @if($comment->status === 'pending')
                                <button wire:click="approve({{ $comment->id }})" class="text-xs font-semibold uppercase tracking-wider text-brand-600 hover:text-black mr-3 transition-colors">Approve</button>
                                <button wire:click="reject({{ $comment->id }})" class="text-xs font-semibold uppercase tracking-wider text-red-600 hover:text-black mr-3 transition-colors">Reject</button>
                            @endif
                            <button wire:click="delete({{ $comment->id }})" wire:confirm="Delete this comment?" class="text-xs font-semibold uppercase tracking-wider text-neutral-600 hover:text-black transition-colors">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-neutral-500">No comments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $comments->links() }}
    </div>
</div>
