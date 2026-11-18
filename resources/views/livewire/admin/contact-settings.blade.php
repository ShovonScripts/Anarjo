<div>
    @section('header', 'Contact Page Settings')

    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight">Contact Page</h1>
        <p class="mt-1 text-sm text-neutral-500">Manage the hero section and FAQ on your contact page.</p>
    </div>

    <div wire:loading.remove wire:target="save" x-data="{ show: false }" x-on:settings-saved.window="show = true; setTimeout(() => show = false, 3000)" x-show="show" class="mb-6 px-4 py-3 bg-black text-white text-sm font-semibold uppercase tracking-wider">
        Settings saved successfully.
    </div>

    {{-- Hero Section --}}
    <div class="border border-neutral-200 bg-white mb-8">
        <div class="px-8 py-6 border-b border-neutral-200">
            <h2 class="text-lg font-bold tracking-tight">Hero Section</h2>
            <p class="mt-1 text-sm text-neutral-500">The top header area with label and title.</p>
        </div>
        <form wire:submit="save" class="px-8 py-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="contact_label" value="Label" />
                    <x-text-input wire:model="contact_label" id="contact_label" class="mt-1 block w-full" placeholder="Get in Touch" />
                    <p class="mt-1 text-xs text-neutral-400">The small uppercase label above the title.</p>
                </div>
                <div>
                    <x-input-label for="contact_hero_title" value="Hero Title" />
                    <x-text-input wire:model="contact_hero_title" id="contact_hero_title" class="mt-1 block w-full" placeholder="Contact Us" />
                </div>
            </div>
            <div>
                <x-input-label for="contact_subtitle" value="Subtitle" />
                <x-text-input wire:model="contact_subtitle" id="contact_subtitle" class="mt-1 block w-full" placeholder="Have a question? We'd love to hear from you." />
                <p class="mt-1 text-xs text-neutral-400">The description text below the title.</p>
            </div>
            <div class="flex items-center gap-4 pt-2">
                <button type="submit" class="btn-primary" wire:loading.attr="disabled">Save Changes</button>
                <div wire:loading wire:target="save" class="text-sm text-neutral-500">Saving...</div>
            </div>
        </form>
    </div>

    {{-- FAQ Section --}}
    <div class="border border-neutral-200 bg-white">
        <div class="px-8 py-6 border-b border-neutral-200">
            <h2 class="text-lg font-bold tracking-tight">Frequently Asked Questions</h2>
            <p class="mt-1 text-sm text-neutral-500">Add, edit, or remove questions and answers displayed on the contact page.</p>
        </div>

        {{-- Add new FAQ --}}
        <div class="px-8 py-6 border-b border-neutral-200 bg-neutral-50">
            <h3 class="text-sm font-bold tracking-tight mb-4">Add New FAQ</h3>
            <div class="space-y-4">
                <div>
                    <x-input-label for="new_question" value="Question" />
                    <x-text-input wire:model="new_question" id="new_question" class="mt-1 block w-full" placeholder="e.g. How long does shipping take?" />
                    @error('new_question') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <x-input-label for="new_answer" value="Answer" />
                    <textarea wire:model="new_answer" id="new_answer" rows="3" class="mt-1 block w-full border border-neutral-300 px-3 py-2 text-sm focus:border-black focus:ring-0 focus:outline-none" placeholder="Write the answer..."></textarea>
                    @error('new_answer') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                </div>
                <button type="button" wire:click="addFaq" class="btn-primary btn-sm">Add FAQ</button>
            </div>
        </div>

        {{-- FAQ List --}}
        <div class="px-8 py-6">
            @forelse($faqs as $index => $faq)
                <div class="border border-neutral-200 mb-4 last:mb-0" wire:key="faq-{{ $index }}">
                    <div class="px-6 py-4 border-b border-neutral-200 flex items-center justify-between bg-neutral-50">
                        <span class="text-sm font-semibold">{{ $faq['question'] }}</span>
                        <div class="flex items-center gap-3">
                            <button wire:click="editFaq({{ $index }})" class="text-xs font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">Edit</button>
                            <button wire:click="deleteFaq({{ $index }})" wire:confirm="Delete this FAQ?" class="text-xs font-semibold uppercase tracking-wider text-brand-600 hover:text-brand-700 transition-colors">Delete</button>
                        </div>
                    </div>
                    <div class="px-6 py-4">
                        <p class="text-sm text-neutral-600 leading-relaxed">{{ $faq['answer'] }}</p>
                    </div>

                    {{-- Edit form inline --}}
                    @if($editIndex === $index)
                        <div class="px-6 py-4 border-t border-neutral-200 bg-neutral-50 space-y-4">
                            <div>
                                <x-input-label for="edit_question" value="Question" />
                                <x-text-input wire:model="edit_question" id="edit_question" class="mt-1 block w-full" />
                                @error('edit_question') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <x-input-label for="edit_answer" value="Answer" />
                                <textarea wire:model="edit_answer" id="edit_answer" rows="3" class="mt-1 block w-full border border-neutral-300 px-3 py-2 text-sm focus:border-black focus:ring-0 focus:outline-none"></textarea>
                                @error('edit_answer') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button" wire:click="updateFaq" class="btn-primary btn-sm">Update</button>
                                <button type="button" wire:click="cancelEdit" class="btn-outline btn-sm">Cancel</button>
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-12 text-neutral-500">
                    <p class="text-sm">No FAQs yet. Add your first question above.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
