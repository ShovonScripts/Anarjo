<div>
    @section('header', 'Blog Posts')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold tracking-tight">Blog Posts</h2>
        <a href="{{ route('admin.blogs.create') }}" wire:navigate class="btn-primary btn-sm">+ New Post</a>
    </div>

    @if(session('message'))
        <div class="border border-black bg-neutral-50 px-4 py-3 mb-6 text-sm font-medium">{{ session('message') }}</div>
    @endif

    {{-- Filters --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <input wire:model.live="search" type="text" placeholder="Search posts..." class="input-field">
        <select wire:model.live="statusFilter" class="input-field">
            <option value="">All Status</option>
            <option value="published">Published</option>
            <option value="draft">Draft</option>
        </select>
        <select wire:model.live="categoryFilter" class="input-field">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- Table --}}
    <div class="border border-neutral-200 bg-white overflow-hidden">
        <table class="w-full">
            <thead class="bg-neutral-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Category</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Author</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse($blogs as $blog)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-4 py-3 text-sm font-medium">{{ $blog->title }}</td>
                        <td class="px-4 py-3 text-sm text-neutral-600">{{ $blog->category?->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm text-neutral-600">{{ $blog->author ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="text-xs font-semibold uppercase tracking-wider {{ $blog->status === 'published' ? 'text-brand-600' : 'text-neutral-400' }}">{{ ucfirst($blog->status) }}</span>
                        </td>
                        <td class="px-4 py-3 text-right text-sm space-x-3">
                            <a href="{{ route('admin.blogs.edit', $blog) }}" wire:navigate class="text-xs font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">Edit</a>
                            <button wire:click="delete({{ $blog->id }})" wire:confirm="Are you sure?" class="text-xs font-semibold uppercase tracking-wider text-brand-600 hover:text-brand-700 transition-colors">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-8 text-center text-neutral-500">No posts found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $blogs->links() }}</div>
</div>
