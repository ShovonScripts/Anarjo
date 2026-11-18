<div>
    @section('header', 'Events')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold tracking-tight">Events</h2>
        <a href="{{ route('admin.events.create') }}" wire:navigate class="btn-primary btn-sm">+ New Event</a>
    </div>

    @if(session('message'))
        <div class="border border-black bg-neutral-50 px-4 py-3 mb-6 text-sm font-medium">{{ session('message') }}</div>
    @endif

    {{-- Filters --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <input wire:model.live="search" type="text" placeholder="Search events..." class="input-field">
        <select wire:model.live="statusFilter" class="input-field">
            <option value="">All Status</option>
            <option value="published">Published</option>
            <option value="draft">Draft</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="border border-neutral-200 bg-white overflow-hidden">
        <table class="w-full">
            <thead class="bg-neutral-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Venue</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider">Major</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse($events as $event)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium">{{ $event->title }}</td>
                        <td class="px-4 py-3 text-sm text-neutral-600">{{ $event->date?->format('M d, Y') ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-neutral-600">{{ $event->venue ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-center">
                            <button wire:click="toggleMajor({{ $event->id }})"
                                class="inline-flex items-center justify-center w-8 h-8 rounded-full transition-colors {{ $event->is_major ? 'bg-yellow-400 text-black' : 'bg-neutral-200 text-neutral-400 hover:bg-neutral-300' }}"
                                title="{{ $event->is_major ? 'Unmark as major' : 'Mark as major' }}">
                                <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </button>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <span class="text-xs font-semibold uppercase tracking-wider {{ $event->status === 'published' ? 'text-brand-600' : 'text-neutral-400' }}">{{ ucfirst($event->status) }}</span>
                        </td>
                        <td class="px-4 py-3 text-right text-sm space-x-3">
                            <a href="{{ route('admin.events.edit', $event) }}" wire:navigate class="text-xs font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">Edit</a>
                            <button wire:click="delete({{ $event->id }})" wire:confirm="Are you sure?" class="text-xs font-semibold uppercase tracking-wider text-brand-600 hover:text-brand-700 transition-colors">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-neutral-500">No events found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $events->links() }}</div>
</div>
