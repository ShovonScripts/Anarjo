<div>
    @section('header', 'About Page Settings')

    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight">About Page</h1>
        <p class="mt-1 text-sm text-neutral-500">Manage the content and layout of your about page.</p>
    </div>

    <div wire:loading.remove wire:target="save" x-data="{ show: false }" x-on:settings-saved.window="show = true; setTimeout(() => show = false, 3000)" x-show="show" class="mb-6 px-4 py-3 bg-black text-white text-sm font-semibold uppercase tracking-wider">
        Settings saved successfully.
    </div>

    {{-- Hero Section --}}
    <div class="border border-neutral-200 bg-white mb-8">
        <div class="px-8 py-6 border-b border-neutral-200">
            <h2 class="text-lg font-bold tracking-tight">Hero Section</h2>
            <p class="mt-1 text-sm text-neutral-500">The top header area with background image, label, and main title.</p>
        </div>
        <form wire:submit="save" class="px-8 py-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="about_label" value="Label" />
                    <x-text-input wire:model="about_label" id="about_label" class="mt-1 block w-full" placeholder="About Us" />
                    <p class="mt-1 text-xs text-neutral-400">The small uppercase label above the title.</p>
                </div>
                <div>
                    <x-input-label for="about_hero_title" value="Hero Title" />
                    <x-text-input wire:model="about_hero_title" id="about_hero_title" class="mt-1 block w-full" placeholder="We believe in the power of words" />
                </div>
            </div>
            <div>
                <x-input-label for="about_subtitle" value="Subtitle" />
                <x-text-input wire:model="about_subtitle" id="about_subtitle" class="mt-1 block w-full" placeholder="Optional description below the title" />
                <p class="mt-1 text-xs text-neutral-400">A short description displayed below the hero title.</p>
            </div>
            <div class="flex items-center gap-4 pt-2">
                <button type="submit" class="btn-primary" wire:loading.attr="disabled">Save Changes</button>
                <div wire:loading wire:target="save" class="text-sm text-neutral-500">Saving...</div>
            </div>
        </form>
    </div>

    {{-- Content Section --}}
    <div class="border border-neutral-200 bg-white mb-8">
        <div class="px-8 py-6 border-b border-neutral-200">
            <h2 class="text-lg font-bold tracking-tight">Content</h2>
            <p class="mt-1 text-sm text-neutral-500">The main body content about your publication.</p>
        </div>
        <form wire:submit="save" class="px-8 py-6 space-y-6">
            <div>
                <x-input-label for="about_content" value="About Text" />
                <p class="mt-1 text-xs text-neutral-400 mb-3">The main description about your publication.</p>
                <div wire:ignore>
                    <div
                        x-ref="editor"
                        x-init="
                            let editorEl = $refs.editor;
                            let quill = new Quill(editorEl, {
                                theme: 'snow',
                                modules: {
                                    toolbar: [
                                        ['bold', 'italic', 'underline', 'strike'],
                                        [{ 'header': [2, 3, false] }],
                                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                                        ['blockquote', 'link'],
                                    ]
                                },
                                placeholder: 'Tell your story... what makes your publication unique?'
                            });
                            if ($wire.get('about_content')) {
                                quill.root.innerHTML = $wire.get('about_content');
                            }
                            quill.on('text-change', function () {
                                let html = quill.root.innerHTML;
                                if (html === '<p><br></p>' || html === '<br>') html = '';
                                $wire.set('about_content', html, false);
                            });
                            quill.on('blur', function () {
                                let html = quill.root.innerHTML;
                                if (html === '<p><br></p>' || html === '<br>') html = '';
                                $wire.set('about_content', html);
                            });
                        "
                        style="height: 250px;"
                    >{!! $about_content !!}</div>
                </div>
                @error('about_content') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="about_mission_heading" value="Mission Heading" />
                    <x-text-input wire:model="about_mission_heading" id="about_mission_heading" class="mt-1 block w-full" placeholder="Our Mission" />
                </div>
                <div>
                    <x-input-label for="about_vision_heading" value="Vision Heading" />
                    <x-text-input wire:model="about_vision_heading" id="about_vision_heading" class="mt-1 block w-full" placeholder="Our Vision" />
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <x-input-label for="about_mission" value="Mission Text" />
                    <div wire:ignore>
                        <div
                            x-ref="missionEditor"
                            x-init="
                                let quill = new Quill($refs.missionEditor, {
                                    theme: 'snow',
                                    modules: {
                                        toolbar: [
                                            ['bold', 'italic', 'underline'],
                                            [{ 'list': 'bullet' }],
                                            ['link'],
                                        ]
                                    },
                                    placeholder: 'Our mission is to...'
                                });
                                if ($wire.get('about_mission')) {
                                    quill.root.innerHTML = $wire.get('about_mission');
                                }
                                quill.on('text-change', function () {
                                    let html = quill.root.innerHTML;
                                    if (html === '<p><br></p>' || html === '<br>') html = '';
                                    $wire.set('about_mission', html, false);
                                });
                                quill.on('blur', function () {
                                    let html = quill.root.innerHTML;
                                    if (html === '<p><br></p>' || html === '<br>') html = '';
                                    $wire.set('about_mission', html);
                                });
                            "
                            style="height: 150px;"
                        >{!! $about_mission !!}</div>
                    </div>
                </div>
                <div>
                    <x-input-label for="about_vision" value="Vision Text" />
                    <div wire:ignore>
                        <div
                            x-ref="visionEditor"
                            x-init="
                                let quill = new Quill($refs.visionEditor, {
                                    theme: 'snow',
                                    modules: {
                                        toolbar: [
                                            ['bold', 'italic', 'underline'],
                                            [{ 'list': 'bullet' }],
                                            ['link'],
                                        ]
                                    },
                                    placeholder: 'We envision a world where...'
                                });
                                if ($wire.get('about_vision')) {
                                    quill.root.innerHTML = $wire.get('about_vision');
                                }
                                quill.on('text-change', function () {
                                    let html = quill.root.innerHTML;
                                    if (html === '<p><br></p>' || html === '<br>') html = '';
                                    $wire.set('about_vision', html, false);
                                });
                                quill.on('blur', function () {
                                    let html = quill.root.innerHTML;
                                    if (html === '<p><br></p>' || html === '<br>') html = '';
                                    $wire.set('about_vision', html);
                                });
                            "
                            style="height: 150px;"
                        >{!! $about_vision !!}</div>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-4 pt-2">
                <button type="submit" class="btn-primary" wire:loading.attr="disabled">Save Changes</button>
                <div wire:loading wire:target="save" class="text-sm text-neutral-500">Saving...</div>
            </div>
        </form>
    </div>

    {{-- Image Slider Section --}}
    <div class="border border-neutral-200 bg-white mb-8" x-data="{ notification: false }" x-on:slide-saved.window="notification = true; setTimeout(() => notification = false, 3000)">
        <div class="px-8 py-6 border-b border-neutral-200 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold tracking-tight">Image Slider</h2>
                <p class="mt-1 text-sm text-neutral-500">Slides displayed after the hero section, before the content.</p>
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

    @push('js')
    <script>
        document.addEventListener('livewire:navigated', function () {
            if (typeof Quill !== 'undefined') {
                document.querySelectorAll('.ql-container').forEach(function (el) {
                    let q = Quill.find(el);
                    if (q) q.destroy();
                });
            }
        });
    </script>
    @endpush
</div>
