<div>
    @if (session('message'))
        <div class="mb-6 px-5 py-4 border border-brand-600 bg-brand-50 text-brand-700 text-sm font-medium">{{ session('message') }}</div>
    @endif

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
                    <th class="px-5 py-3"></th>
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
                        <td class="px-5 py-4 text-right">
                            <button wire:click="edit({{ $user->id }})" class="text-xs font-semibold uppercase tracking-wider text-neutral-600 hover:text-black transition-colors">Edit</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-neutral-500">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $users->links() }}</div>

    {{-- Modal --}}
    @if($isOpen)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center" wire:click.self="$set('isOpen', false)">
            <div class="bg-white w-full max-w-lg p-8">
                <h3 class="text-lg font-bold tracking-tight mb-6">Edit User</h3>
                <form wire:submit="store" class="space-y-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Name</label>
                        <input type="text" wire:model="name" class="w-full px-4 py-2.5 border border-neutral-300 text-sm focus:outline-none focus:border-black transition-colors">
                        @error('name') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Email</label>
                        <input type="email" wire:model="email" class="w-full px-4 py-2.5 border border-neutral-300 text-sm focus:outline-none focus:border-black transition-colors">
                        @error('email') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Phone</label>
                        <input type="text" wire:model="phone" class="w-full px-4 py-2.5 border border-neutral-300 text-sm focus:outline-none focus:border-black transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Status</label>
                        <select wire:model="status" class="w-full px-4 py-2.5 border border-neutral-300 text-sm focus:outline-none focus:border-black transition-colors">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-3 pt-4 border-t border-neutral-200">
                        <button type="button" wire:click="$set('isOpen', false)" class="px-5 py-2.5 text-sm font-semibold text-neutral-600 hover:text-black transition-colors">Cancel</button>
                        <button type="submit" class="px-5 py-2.5 text-sm font-semibold bg-black text-white hover:bg-neutral-800 transition-colors">Update</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
