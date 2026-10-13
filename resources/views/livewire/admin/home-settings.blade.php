<div>
    @section('header', 'Home Page Settings')

    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight">Home Page</h1>
        <p class="mt-1 text-sm text-neutral-500">Manage the content and appearance of your homepage.</p>
    </div>

    <div wire:loading.remove wire:target="save" x-data="{ show: false }" x-on:settings-saved.window="show = true; setTimeout(() => show = false, 3000)" x-show="show" class="mb-6 px-4 py-3 bg-black text-white text-sm font-semibold uppercase tracking-wider">
        Settings saved successfully.
    </div>

    {{-- About Section --}}
    <div class="border border-neutral-200 bg-white mb-8">
        <div class="px-8 py-6 border-b border-neutral-200">
            <h2 class="text-lg font-bold tracking-tight">About Section</h2>
            <p class="mt-1 text-sm text-neutral-500">The top Bangla introduction section with background image.</p>
        </div>
        <form wire:submit="save" class="px-8 py-6 space-y-6">
            <div>
                <x-input-label for="home_section_title" value="Section Title" />
                <x-text-input wire:model="home_section_title" id="home_section_title" class="mt-1 block w-full" placeholder="আনারজো পাবলিকেশনস" />
                <p class="mt-1 text-xs text-neutral-400">The small uppercase label above the Bangla text.</p>
            </div>
            <div>
                <x-input-label for="home_about_image" value="Background Image" />
                <p class="text-xs text-neutral-400 mb-3">Recommended: 1920x800px. Shows as a full-width hero background.</p>
                @if ($home_about_image_path)
                    <div class="flex items-center gap-4 mb-4">
                        <img src="{{ asset('storage/' . $home_about_image_path) }}" class="h-24 border border-neutral-200">
                        <button type="button" wire:click="removeImage" class="text-sm text-brand-600 hover:text-brand-700">Remove</button>
                    </div>
                @endif
                <input type="file" wire:model="home_about_image" accept="image/jpeg,image/png,image/webp" class="block w-full text-sm text-neutral-500 file:mr-4 file:py-2 file:px-4 file:border file:border-neutral-300 file:text-sm file:font-semibold file:bg-white file:text-black hover:file:bg-neutral-50">
                <x-input-error :messages="$errors->get('home_about_image')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="home_about_bangla" value="Bangla Text" />
                <p class="mt-1 text-xs text-neutral-400 mb-3">Displayed on the dark overlay.</p>
                <div wire:ignore>
                    <div
                        x-ref="banglaEditor"
                        x-init="
                            let quill = new Quill($refs.banglaEditor, {
                                theme: 'snow',
                                modules: {
                                    toolbar: [
                                        ['bold', 'italic', 'underline'],
                                        [{ 'list': 'bullet' }],
                                        ['link'],
                                    ]
                                },
                                placeholder: 'আনারজো সম্পর্কে বাংলা লেখা...'
                            });
                            if ($wire.get('home_about_bangla')) {
                                quill.root.innerHTML = $wire.get('home_about_bangla');
                            }
                            quill.on('text-change', function () {
                                let html = quill.root.innerHTML;
                                if (html === '<p><br></p>' || html === '<br>') html = '';
                                $wire.set('home_about_bangla', html, false);
                            });
                            quill.on('blur', function () {
                                let html = quill.root.innerHTML;
                                if (html === '<p><br></p>' || html === '<br>') html = '';
                                $wire.set('home_about_bangla', html);
                            });
                        "
                        style="height: 200px;"
                    >{!! $home_about_bangla !!}</div>
                </div>
            </div>
            <div class="flex items-center gap-4 pt-2">
                <button type="submit" class="btn-primary" wire:loading.attr="disabled">Save Changes</button>
                <div wire:loading wire:target="save" class="text-sm text-neutral-500">Saving...</div>
            </div>
        </form>
    </div>

    {{-- Branding Boxes Section --}}
    <div class="border border-neutral-200 bg-white mb-8">
        <div class="px-8 py-6 border-b border-neutral-200">
            <h2 class="text-lg font-bold tracking-tight">Branding Boxes</h2>
            <p class="mt-1 text-sm text-neutral-500">Four ad images shown side by side on desktop and as a scrolling marquee on mobile. Upload images and optional links.</p>
        </div>
        <form wire:submit="save" class="px-8 py-6 space-y-8">
            @foreach([1, 2, 3, 4] as $i)
            <div class="border border-neutral-200 p-6 {{ $i > 1 ? 'border-t' : '' }}">
                <h3 class="text-sm font-semibold uppercase tracking-widest mb-4">Box {{ $i }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <x-input-label for="boxes.{{ $i }}.title" value="Title (optional)" />
                        <x-text-input wire:model="boxes.{{ $i }}.title" id="boxes.{{ $i }}.title" class="mt-1 block w-full" placeholder="e.g. New Arrivals" />
                    </div>
                    <div>
                        <x-input-label for="boxes.{{ $i }}.link" value="Link (optional)" />
                        <x-text-input wire:model="boxes.{{ $i }}.link" id="boxes.{{ $i }}.link" class="mt-1 block w-full" placeholder="https://... or /books" />
                    </div>
                </div>
                <div class="mt-4">
                    <x-input-label for="boxes.{{ $i }}.image" value="Image" />
                    <p class="text-xs text-neutral-400 mb-3">Recommended: 600x400px.</p>
                    @if ($boxes[$i]['image_path'])
                        <div class="flex items-center gap-4 mb-4">
                            <img src="{{ asset('storage/' . $boxes[$i]['image_path']) }}" class="h-24 border border-neutral-200">
                            <button type="button" wire:click="removeBoxImage({{ $i }})" class="text-sm text-brand-600 hover:text-brand-700">Remove</button>
                        </div>
                    @endif
                    <input type="file" wire:model="boxes.{{ $i }}.image" accept="image/jpeg,image/png,image/webp" class="block w-full text-sm text-neutral-500 file:mr-4 file:py-2 file:px-4 file:border file:border-neutral-300 file:text-sm file:font-semibold file:bg-white file:text-black hover:file:bg-neutral-50">
                    @error("boxes.{$i}.image") <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
            @endforeach

            <div class="flex items-center gap-4 pt-2">
                <button type="submit" class="btn-primary" wire:loading.attr="disabled">Save Changes</button>
                <div wire:loading wire:target="save" class="text-sm text-neutral-500">Saving...</div>
            </div>
        </form>
    </div>

    {{-- Hero Slider Section --}}
    <div class="border border-neutral-200 bg-white" x-data="{ notification: false }" x-on:slide-saved.window="notification = true; setTimeout(() => notification = false, 3000)">
        <div class="px-8 py-6 border-b border-neutral-200 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold tracking-tight">Hero Slider</h2>
                <p class="mt-1 text-sm text-neutral-500">Slides that appear below the about section. Each image is fully clickable.</p>
            </div>
            <button wire:click="createSlide" class="btn-primary btn-sm">+ Add Slide</button>
        </div>

        <div x-show="notification" class="border-b border-neutral-200 px-8 py-3 bg-black text-white text-sm font-semibold uppercase tracking-wider">
            Slide saved.
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-neutral-100">
                    <tr>
                        <th class="px-8 py-3 text-left text-xs font-semibold uppercase tracking-wider">Image</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Link</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Order</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                        <th class="px-8 py-3 text-right text-xs font-semibold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200">
                    @forelse($slides as $slide)
                        <tr class="hover:bg-neutral-50 transition-colors">
                            <td class="px-8 py-3">
                                @if($slide->image)
                                    <img src="{{ asset('storage/' . $slide->image) }}" class="w-20 h-12 object-cover border border-neutral-200">
                                @else
                                    <span class="text-xs text-neutral-400">No image</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-neutral-500 max-w-[200px] truncate">{{ $slide->link ?: '—' }}</td>
                            <td class="px-4 py-3 text-sm">{{ $slide->sort_order }}</td>
                            <td class="px-4 py-3">
                                <button wire:click="toggleSlide({{ $slide->id }})" class="text-xs font-semibold uppercase tracking-wider {{ $slide->status ? 'text-brand-600' : 'text-neutral-400' }}">
                                    {{ $slide->status ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-8 py-3 text-right text-sm space-x-3">
                                <button wire:click="editSlide({{ $slide->id }})" class="text-xs font-semibold uppercase tracking-wider text-black hover:text-brand-600 transition-colors">Edit</button>
                                <button wire:click="deleteSlide({{ $slide->id }})" wire:confirm="Are you sure?" class="text-xs font-semibold uppercase tracking-wider text-brand-600 hover:text-brand-700 transition-colors">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-8 py-8 text-center text-neutral-500">No slides yet. Click "Add Slide" to create one.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($slides->hasPages())
            <div class="px-8 py-4 border-t border-neutral-200">{{ $slides->links() }}</div>
        @endif
    </div>

    {{-- Slide Modal --}}
    @if($slideModal)
    <div class="fixed inset-0 bg-black/60 z-50 flex items-start justify-center pt-10 overflow-y-auto">
        <div class="bg-white border border-black w-full max-w-xl mx-4 p-8">
            <h3 class="text-lg font-bold tracking-tight mb-6">{{ $slide_id ? 'Edit Slide' : 'Add Slide' }}</h3>

            <form wire:submit="storeSlide" class="space-y-5">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Image</label>
                    <input wire:model="slide_new_image" type="file" accept="image/*" class="w-full text-sm">
                    @if($slide_image && !$slide_new_image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $slide_image) }}" class="h-40 object-cover border border-neutral-200">
                        </div>
                    @endif
                    @error('slide_new_image') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Link (optional)</label>
                    <input wire:model="slide_link" class="input-field" placeholder="https://... or /books">
                    <p class="mt-1 text-xs text-neutral-400">The full image becomes clickable. Leave empty for no link.</p>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Sort Order</label>
                        <input wire:model="slide_sort_order" type="number" min="0" class="input-field">
                        @error('slide_sort_order') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex items-end pb-2">
                        <label class="flex items-center gap-2 text-sm">
                            <input wire:model="slide_status" type="checkbox" class="border-neutral-300 text-black focus:ring-black">
                            <span>Active</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-neutral-200">
                    <button type="button" wire:click="$set('slideModal', false)" class="btn-outline btn-sm">Cancel</button>
                    <button type="submit" class="btn-primary btn-sm">{{ $slide_id ? 'Update' : 'Save' }}</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
