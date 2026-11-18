<div>
    @section('header', 'Books')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold tracking-tight">Books</h2>
        <button wire:click="create" class="btn-primary btn-sm">+ Add Book</button>
    </div>

    @if(session('message'))
        <div class="border border-black bg-neutral-50 px-4 py-3 mb-6 text-sm font-medium">{{ session('message') }}</div>
    @endif

    <div class="mb-4">
        <input wire:model.live="search" type="text" placeholder="Search books..." class="input-field">
    </div>

    <div class="border border-neutral-200 bg-white overflow-hidden">
        <table class="w-full">
            <thead class="bg-neutral-100">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Title</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Author</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Price</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Stock</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-200">
                @forelse($books as $book)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-4 py-3 text-sm">{{ $book->title }}</td>
                        <td class="px-4 py-3 text-sm text-neutral-600">{{ $book->author?->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-sm font-semibold">৳{{ number_format($book->sale_price ?? $book->price) }}</td>
                        <td class="px-4 py-3 text-sm">{{ $book->stock }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="text-xs font-semibold uppercase tracking-wider {{ $book->status === 'published' ? 'text-brand-600' : ($book->status === 'draft' ? 'text-neutral-400' : 'text-neutral-500') }}">
                                {{ ucfirst($book->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right text-sm space-x-3">
                            <button wire:click="edit({{ $book->id }})" class="text-xs font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">Edit</button>
                            <button wire:click="delete({{ $book->id }})" wire:confirm="Are you sure?" class="text-xs font-semibold uppercase tracking-wider text-brand-600 hover:text-brand-700 transition-colors">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-neutral-500">No books found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $books->links() }}</div>

    {{-- Modal --}}
    @if($isOpen)
    <div class="fixed inset-0 bg-black/60 z-50 flex items-start justify-center pt-10 overflow-y-auto">
        <div class="bg-white border border-black w-full max-w-2xl mx-4 p-8">
            <h3 class="text-lg font-bold tracking-tight mb-6">{{ $book_id ? 'Edit Book' : 'Add Book' }}</h3>

            <form wire:submit="store" class="space-y-5">
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Title</label>
                        <input wire:model="title" class="input-field">
                        @error('title') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">ISBN</label>
                        <input wire:model="isbn" class="input-field">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Author</label>
                        <select wire:model="author_id" class="input-field">
                            <option value="">Select Author</option>
                            @foreach($authors as $author)
                                <option value="{{ $author->id }}">{{ $author->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Publisher</label>
                        <select wire:model="publisher_id" class="input-field">
                            <option value="">Select Publisher</option>
                            @foreach($publishers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Price</label>
                        <input wire:model="price" type="number" step="0.01" class="input-field">
                        @error('price') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Sale Price</label>
                        <input wire:model="sale_price" type="number" step="0.01" class="input-field">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Stock</label>
                        <input wire:model="stock" type="number" class="input-field">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Language</label>
                        <input wire:model="language" class="input-field">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Pages</label>
                        <input wire:model="pages" type="number" class="input-field">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Edition</label>
                        <input wire:model="edition" class="input-field">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Publish Date</label>
                        <input wire:model="publish_date" type="date" class="input-field">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Weight (kg)</label>
                        <input wire:model="weight" type="number" step="0.01" class="input-field">
                    </div>
                </div>

                {{-- Categories (multi-select) --}}
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Categories</label>
                    @error('selectedCategories') <span class="text-xs text-brand-600 mt-1 block">{{ $message }}</span> @enderror
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-48 overflow-y-auto border border-neutral-200 p-3">
                        @forelse($categories as $cat)
                            <label class="flex items-center gap-2 text-sm cursor-pointer hover:bg-neutral-50 px-2 py-1">
                                <input wire:model="selectedCategories" type="checkbox" value="{{ $cat->id }}" class="border-neutral-300 text-black focus:ring-black">
                                <span>{{ $cat->name }}</span>
                            </label>
                        @empty
                            <p class="text-sm text-neutral-500 col-span-3">No categories available.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Book Fair Editions --}}
                @if($fairEditions->count())
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Book Fair Editions</label>
                    @error('selectedFairEditions') <span class="text-xs text-brand-600 mt-1 block">{{ $message }}</span> @enderror
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2 border border-neutral-200 p-3">
                        @foreach($fairEditions as $fe)
                            <label class="flex items-center gap-2 text-sm cursor-pointer hover:bg-neutral-50 px-2 py-1">
                                <input wire:model="selectedFairEditions" type="checkbox" value="{{ $fe->id }}" class="border-neutral-300 text-black focus:ring-black">
                                <span>{{ $fe->title }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                @endif

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Description</label>
                    <textarea wire:model="description" rows="3" class="input-field"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">SEO Title</label>
                        <input wire:model="seo_title" class="input-field">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">SEO Description</label>
                        <input wire:model="seo_description" class="input-field">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Cover Image</label>
                    <input wire:model="new_cover_image" type="file" accept="image/*" class="w-full text-sm">
                    @if($cover_image && !$new_cover_image)
                        <p class="text-xs text-neutral-500 mt-1">Current: {{ $cover_image }}</p>
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Preview PDF</label>
                    <input wire:model="new_preview_pdf" type="file" accept=".pdf" class="w-full text-sm">
                    @if($preview_pdf && !$new_preview_pdf)
                        <p class="text-xs text-neutral-500 mt-1">Current: {{ $preview_pdf }}</p>
                    @endif
                    @error('new_preview_pdf') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="flex flex-wrap gap-6">
                    <label class="flex items-center gap-2 text-sm">
                        <input wire:model="featured" type="checkbox" class="border-neutral-300 text-black focus:ring-black">
                        <span>Featured</span>
                    </label>
                    <label class="flex items-center gap-2 text-sm">
                        <input wire:model="bestseller" type="checkbox" class="border-neutral-300 text-black focus:ring-black">
                        <span>Bestseller</span>
                    </label>
                    <label class="flex items-center gap-2 text-sm">
                        <input wire:model="new_arrival" type="checkbox" class="border-neutral-300 text-black focus:ring-black">
                        <span>New Arrival</span>
                    </label>
                    <label class="flex items-center gap-2 text-sm">
                        <input wire:model="pre_order" type="checkbox" class="border-neutral-300 text-black focus:ring-black">
                        <span>Pre Order</span>
                    </label>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Status</label>
                    <select wire:model="status" class="input-field">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-neutral-200">
                    <button type="button" wire:click="$set('isOpen', false)" class="btn-outline btn-sm">Cancel</button>
                    <button type="submit" class="btn-primary btn-sm">{{ $book_id ? 'Update' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
