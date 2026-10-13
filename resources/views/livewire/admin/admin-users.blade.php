<div>
    @section('header', 'Admin Management')
    @if(!auth()->user()->hasRole('Super Admin'))
        <div class="mb-6 px-5 py-4 border border-amber-300 bg-amber-50 text-amber-800 text-sm font-medium">You have limited access. Only Super Admins can manage other Super Admin accounts.</div>
    @endif
    @if (session('message'))
        <div class="mb-6 px-5 py-4 border border-brand-600 bg-brand-50 text-brand-700 text-sm font-medium">{{ session('message') }}</div>
    @endif

    <div class="flex justify-between items-center mb-8">
        <div class="w-64">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search admins..." class="w-full px-4 py-2.5 border border-neutral-300 text-sm focus:outline-none focus:border-black transition-colors">
        </div>
        <div class="flex items-center gap-4">
            <span class="text-sm text-neutral-500">{{ $users->total() }} admins</span>
            <button wire:click="create" class="btn-primary btn-sm">+ Add Admin</button>
        </div>
    </div>

    <div class="border border-neutral-200 bg-white overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-neutral-50 border-b border-neutral-200 text-left">
                    <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider">Name</th>
                    <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider">Email</th>
                    <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider">Phone</th>
                    <th class="px-5 py-3 font-semibold text-xs uppercase tracking-wider">Role</th>
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
                        <td class="px-5 py-4">
                            <span class="text-xs font-semibold uppercase tracking-wider {{ $user->hasRole('Super Admin') ? 'text-amber-600' : 'text-brand-600' }}">
                                {{ $user->hasRole('Super Admin') ? 'Super Admin' : 'Admin' }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-xs text-neutral-500">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-4">
                            <span class="text-xs font-semibold uppercase tracking-wider {{ ($user->status ?? 'active') === 'active' ? 'text-brand-600' : 'text-neutral-400' }}">{{ $user->status ?? 'active' }}</span>
                        </td>
                        <td class="px-5 py-4 text-right space-x-3">
                            @if(!$user->hasRole('Super Admin') || auth()->user()->hasRole('Super Admin'))
                                <button wire:click="edit({{ $user->id }})" class="text-xs font-semibold uppercase tracking-wider text-neutral-600 hover:text-black transition-colors">Edit</button>
                                @if($user->id !== auth()->id())
                                <button wire:click="confirmDelete({{ $user->id }})" class="text-xs font-semibold uppercase tracking-wider text-brand-600 hover:text-brand-700 transition-colors">Delete</button>
                                @endif
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-neutral-500">No admins found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $users->links() }}</div>

    {{-- Create/Edit Modal --}}
    @if($isOpen)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center" wire:click.self="$set('isOpen', false)">
            <div class="bg-white w-full max-w-lg p-8">
                <h3 class="text-lg font-bold tracking-tight mb-6">{{ $user_id ? 'Edit Admin' : 'Add Admin' }}</h3>
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
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Password {{ $user_id ? '(leave blank to keep current)' : '' }}</label>
                        <input type="password" wire:model="password" class="w-full px-4 py-2.5 border border-neutral-300 text-sm focus:outline-none focus:border-black transition-colors">
                        @error('password') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Confirm Password</label>
                        <input type="password" wire:model="password_confirmation" class="w-full px-4 py-2.5 border border-neutral-300 text-sm focus:outline-none focus:border-black transition-colors">
                        @error('password_confirmation') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
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
                        <button type="submit" wire:loading.attr="disabled" class="px-5 py-2.5 text-sm font-semibold bg-black text-white hover:bg-neutral-800 transition-colors disabled:opacity-50">
                            <span wire:loading.remove wire:target="store">{{ $user_id ? 'Update' : 'Create' }}</span>
                            <span wire:loading wire:target="store" class="inline-flex items-center gap-2">
                                <svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                Saving…
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Delete Confirmation Modal --}}
    @if($deleteId)
        <div class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center" wire:click.self="cancelDelete">
            <div class="bg-white w-full max-w-sm p-8 text-center">
                <h3 class="text-lg font-bold tracking-tight mb-2">Delete Admin</h3>
                <p class="text-sm text-neutral-600 mb-6">Are you sure you want to delete this admin? This action cannot be undone.</p>
                <div class="flex justify-center gap-3">
                    <button wire:click="cancelDelete" class="px-5 py-2.5 text-sm font-semibold text-neutral-600 hover:text-black transition-colors">Cancel</button>
                    <button wire:click="delete" class="px-5 py-2.5 text-sm font-semibold bg-brand-600 text-white hover:bg-brand-700 transition-colors">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
