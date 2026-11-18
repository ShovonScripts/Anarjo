<div>
    @section('header', 'User Management')

    <div class="flex justify-between items-center mb-8">
        <div class="w-64">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search users..." class="w-full px-4 py-2.5 border border-neutral-300 text-sm focus:outline-none focus:border-black transition-colors">
        </div>
        <span class="text-sm text-neutral-500">{{ $users->total() }} users</span>
    </div>

    <div class="border border-neutral-200 bg-white overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-neutral-50 border-b border-neutral-200 text-left">
                    <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider">Name</th>
                    <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider">Email</th>
                    <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider">Phone</th>
                    <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider">Joined</th>
                    <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse($users as $user)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-5 py-4 font-semibold">{{ $user->name }}</td>
                        <td class="px-5 py-4 text-neutral-600">{{ $user->email }}</td>
                        <td class="px-5 py-4 text-neutral-600">{{ $user->phone ?? '-' }}</td>
                        <td class="px-5 py-4 text-xs text-neutral-500">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-4">
                            <span class="text-xs font-semibold uppercase tracking-wider {{ ($user->status ?? 'active') === 'active' ? 'text-brand-600' : 'text-neutral-400' }}">{{ $user->status ?? 'active' }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-neutral-500">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $users->links() }}</div>
</div>
