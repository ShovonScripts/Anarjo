<div>
    @if($submitted)
        <div class="border border-black bg-neutral-50 px-8 py-10 text-center">
            <p class="text-4xl mb-4 text-neutral-400">+</p>
            <h3 class="text-xl font-bold mb-2">Submission Received!</h3>
            <p class="text-neutral-600">Thank you for submitting your manuscript. Our editorial team will review it and get back to you.</p>
        </div>
    @else
        <form wire:submit="submit" class="space-y-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Author Name *</label>
                    <input wire:model="author_name" class="input-field">
                    @error('author_name') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Email *</label>
                    <input wire:model="email" type="email" class="input-field">
                    @error('email') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Phone</label>
                    <input wire:model="phone" class="input-field">
                </div>
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Genre</label>
                    <input wire:model="genre" placeholder="e.g. Fiction, Academic, Islamic" class="input-field">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Book Title *</label>
                <input wire:model="book_title" class="input-field">
                @error('book_title') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Manuscript File (PDF/DOC)</label>
                <input wire:model="file" type="file" accept=".pdf,.doc,.docx" class="w-full text-sm">
                @error('file') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn-primary w-full">Submit Manuscript</button>
        </form>
    @endif
</div>
