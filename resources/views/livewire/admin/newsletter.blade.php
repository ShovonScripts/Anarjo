<div>
    @section('header', 'Newsletter')

    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight">Newsletter Subscribers</h1>
        <p class="mt-1 text-sm text-neutral-500">Manage email subscribers for your newsletter.</p>
    </div>

    <div class="border border-neutral-200 bg-white overflow-hidden">
        <div class="p-5 border-b border-neutral-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="w-full sm:w-64">
                <input wire:model.live.debounce="search" type="text" placeholder="Search by email..."
                       class="input-field w-full text-sm">
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm text-neutral-500">{{ \App\Models\NewsletterSubscriber::where('is_active', true)->count() }} active subscribers</span>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-neutral-50 border-b border-neutral-200">
                    <tr>
                        <th class="text-left px-5 py-3 font-semibold text-xs uppercase tracking-wider">Email</th>
                        <th class="text-left px-5 py-3 font-semibold text-xs uppercase tracking-wider">Subscribed</th>
                        <th class="text-center px-5 py-3 font-semibold text-xs uppercase tracking-wider">Status</th>
                        <th class="text-right px-5 py-3 font-semibold text-xs uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @forelse($subscribers as $sub)
                        <tr class="hover:bg-neutral-50 transition-colors">
                            <td class="px-5 py-4 font-medium">{{ $sub->email }}</td>
                            <td class="px-5 py-4 text-neutral-500">{{ $sub->created_at->format('M d, Y') }}</td>
                            <td class="px-5 py-4 text-center">
                                <button wire:click="toggleActive({{ $sub->id }})"
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider">
                                    @if($sub->is_active)
                                        <span class="w-2 h-2 rounded-full bg-green-600"></span>
                                        <span class="text-green-700">Active</span>
                                    @else
                                        <span class="w-2 h-2 rounded-full bg-neutral-400"></span>
                                        <span class="text-neutral-500">Inactive</span>
                                    @endif
                                </button>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <button wire:click="delete({{ $sub->id }})" wire:confirm="Unsubscribe this email?"
                                        class="text-xs text-red-600 hover:text-red-800 font-semibold uppercase tracking-wider">
                                    Remove
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-12 text-center text-neutral-500">
                                @if($search)
                                    No subscribers matching "{{ $search }}"
                                @else
                                    No subscribers yet.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subscribers->hasPages())
            <div class="border-t border-neutral-200 px-5 py-3">{{ $subscribers->links() }}</div>
        @endif
    </div>
</div>
