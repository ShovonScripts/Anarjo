<div>
    @section('header', $event_id ? 'Edit Event' : 'New Event')
    <div class="mb-6">
        <a href="{{ route('admin.events') }}" wire:navigate class="text-sm font-semibold uppercase tracking-wider text-neutral-500 hover:text-black transition-colors">&larr; Back to Events</a>
    </div>

    @if($errors->any())
        <div class="border border-brand-600 bg-red-50 px-4 py-3 mb-6 text-sm font-medium">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="border border-neutral-200 bg-white">
        <div class="px-8 py-6 border-b border-neutral-200">
            <h2 class="text-lg font-bold tracking-tight">{{ $event_id ? 'Edit Event' : 'New Event' }}</h2>
        </div>

        <form wire:submit="save" class="px-8 py-6">
            <div class="grid grid-cols-3 gap-8">
                {{-- Left Column --}}
                <div class="col-span-2 space-y-6">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Title <span class="text-brand-600">*</span></label>
                        <input wire:model.live="title" class="input-field" placeholder="Enter event title">
                        @error('title') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                        <p class="text-xs text-neutral-400 mt-1">Slug: /events/<span x-text="$wire.title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') || 'untitled'"></span></p>
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Date</label>
                            <input wire:model="date" type="date" class="input-field">
                            @error('date') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Venue</label>
                            <input wire:model="venue" class="input-field" placeholder="Event location">
                            @error('venue') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Description</label>
                        <div wire:ignore>
                            <div
                                x-ref="editor"
                                x-init="
                                    let editorEl = $refs.editor;
                                    let quill = new Quill(editorEl, {
                                        theme: 'snow',
                                        modules: {
                                            toolbar: {
                                                container: [
                                                    ['bold', 'italic', 'underline', 'strike'],
                                                    [{ 'header': [2, 3, false] }],
                                                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                                                    ['blockquote', 'code-block'],
                                                    ['link', 'image'],
                                                ],
                                                handlers: {
                                                    image: function () {
                                                        let input = document.createElement('input');
                                                        input.type = 'file';
                                                        input.accept = 'image/*';
                                                        input.onchange = function () {
                                                            let file = input.files[0];
                                                            if (!file) return;
                                                            let formData = new FormData();
                                                            formData.append('image', file);
                                                            formData.append('_token', '{{ csrf_token() }}');
                                                            let range = quill.getSelection(true);
                                                            quill.enable(false);
                                                            fetch('{{ route('admin.upload.image') }}', {
                                                                method: 'POST',
                                                                body: formData
                                                            }).then(function (res) { return res.json(); })
                                                            .then(function (result) {
                                                                if (result.location) {
                                                                    quill.insertEmbed(range.index, 'image', result.location);
                                                                    quill.setSelection(range.index + 1);
                                                                }
                                                                quill.enable(true);
                                                            }).catch(function () {
                                                                quill.enable(true);
                                                            });
                                                        };
                                                        input.click();
                                                    }
                                                }
                                            }
                                        },
                                        placeholder: 'Describe the event...'
                                    });
                                    if ($wire.get('description')) {
                                        quill.root.innerHTML = $wire.get('description');
                                    }
                                    quill.on('text-change', function () {
                                        let html = quill.root.innerHTML;
                                        if (html === '<p><br></p>' || html === '<br>') html = '';
                                        $wire.set('description', html, false);
                                    });
                                    quill.on('blur', function () {
                                        let html = quill.root.innerHTML;
                                        if (html === '<p><br></p>' || html === '<br>') html = '';
                                        $wire.set('description', html);
                                    });
                                    window.__eventEditor = quill;
                                "
                                style="height: 300px;"
                            >{!! $description !!}</div>
                        </div>
                        @error('description') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Status</label>
                        <select wire:model="status" class="input-field">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>

                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" wire:model="is_major" class="w-4 h-4 rounded border-neutral-300 text-yellow-500 focus:ring-yellow-500">
                            <span class="text-xs font-semibold uppercase tracking-widest">Mark as Major Event</span>
                        </label>
                        <p class="text-xs text-neutral-400 mt-1">Major events will be highlighted with a golden background on the events page.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Featured Image</label>
                        @if($image)
                            <div class="mb-2">
                                <img src="{{ $image->temporaryUrl() }}" class="h-32 w-full object-cover border border-neutral-200">
                                <button type="button" wire:click="removeImage" class="text-xs text-brand-600 mt-1">Remove</button>
                            </div>
                        @elseif($existing_image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $existing_image) }}" class="h-32 w-full object-cover border border-neutral-200">
                                <button type="button" wire:click="removeImage" class="text-xs text-brand-600 mt-1">Remove</button>
                            </div>
                        @endif
                        <input type="file" wire:model="image" accept="image/*" class="block w-full text-sm text-neutral-500 file:mr-4 file:py-2 file:px-4 file:border file:border-neutral-300 file:text-sm file:font-semibold file:bg-white file:text-black hover:file:bg-neutral-50">
                        @error('image') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4 pt-6 mt-6 border-t border-neutral-200">
                <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">{{ $event_id ? 'Update Event' : 'Publish Event' }}</span>
                    <span wire:loading wire:target="save">Saving...</span>
                </button>
                <a href="{{ route('admin.events') }}" wire:navigate class="btn-outline">Cancel</a>
            </div>
        </form>
    </div>

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
