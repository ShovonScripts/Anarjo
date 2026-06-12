<div>
    @section('header', $blog_id ? 'Edit Post' : 'New Post')
    <div class="mb-6">
        <a href="{{ route('admin.blogs') }}" wire:navigate class="text-sm font-semibold uppercase tracking-wider text-neutral-500 hover:text-black transition-colors">&larr; Back to Posts</a>
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
            <h2 class="text-lg font-bold tracking-tight">{{ $blog_id ? 'Edit Post' : 'New Post' }}</h2>
        </div>

        <form wire:submit="save" class="px-8 py-6">
            <div class="grid grid-cols-3 gap-8">
                {{-- Left Column --}}
                <div class="col-span-2 space-y-6">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Title <span class="text-brand-600">*</span></label>
                        <input wire:model.live="title" class="input-field" placeholder="Enter post title">
                        @error('title') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                        <p class="text-xs text-neutral-400 mt-1" x-data x-init>Slug: /blog/<span x-text="$wire.title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') || 'untitled'"></span></p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Content</label>
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
                                        placeholder: 'Write your post content here...'
                                    });
                                    if ($wire.get('content')) {
                                        quill.root.innerHTML = $wire.get('content');
                                    }
                                    quill.on('text-change', function () {
                                        let html = quill.root.innerHTML;
                                        if (html === '<p><br></p>' || html === '<br>') html = '';
                                        $wire.set('content', html, false);
                                    });
                                    quill.on('blur', function () {
                                        let html = quill.root.innerHTML;
                                        if (html === '<p><br></p>' || html === '<br>') html = '';
                                        $wire.set('content', html);
                                    });
                                    window.__blogEditor = quill;
                                "
                                style="height: 400px;"
                            >{!! $content !!}</div>
                        </div>
                        @error('content') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                {{-- Right Column --}}
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Category</label>
                        <select wire:model="blog_category_id" class="input-field">
                            <option value="">Uncategorized</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Tags</label>
                        <div class="max-h-40 overflow-y-auto border border-neutral-200 p-2 space-y-1">
                            @forelse($allTags as $tag)
                                <label class="flex items-center gap-2 text-sm cursor-pointer">
                                    <input type="checkbox" wire:model="tags" value="{{ $tag->id }}" class="border-neutral-300 text-black focus:ring-black">
                                    {{ $tag->name }}
                                </label>
                            @empty
                                <p class="text-xs text-neutral-400 p-2">No tags yet. <a href="{{ route('admin.blog-tags') }}" class="text-brand-600">Create one</a></p>
                            @endforelse
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Author</label>
                        <input wire:model="author" class="input-field" placeholder="Author name">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Status</label>
                        <select wire:model="status" class="input-field">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Featured Image</label>
                        @if($featured_image)
                            <div class="mb-2">
                                <img src="{{ $featured_image->temporaryUrl() }}" class="h-32 w-full object-cover border border-neutral-200">
                                <button type="button" wire:click="removeFeaturedImage" class="text-xs text-brand-600 mt-1">Remove</button>
                            </div>
                        @elseif($existing_featured_image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $existing_featured_image) }}" class="h-32 w-full object-cover border border-neutral-200">
                                <button type="button" wire:click="removeFeaturedImage" class="text-xs text-brand-600 mt-1">Remove</button>
                            </div>
                        @endif
                        <input type="file" wire:model="featured_image" accept="image/*" class="block w-full text-sm text-neutral-500 file:mr-4 file:py-2 file:px-4 file:border file:border-neutral-300 file:text-sm file:font-semibold file:bg-white file:text-black hover:file:bg-neutral-50">
                        @error('featured_image') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="border-t border-neutral-200 pt-4">
                        <h4 class="text-xs font-semibold uppercase tracking-widest mb-3">SEO</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-widest mb-1">Meta Title</label>
                                <input wire:model="meta_title" class="input-field" placeholder="SEO title">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-widest mb-1">Meta Description</label>
                                <textarea wire:model="meta_description" rows="3" class="input-field" placeholder="SEO description"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4 pt-6 mt-6 border-t border-neutral-200">
                <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">{{ $blog_id ? 'Update Post' : 'Publish Post' }}</span>
                    <span wire:loading wire:target="save">Saving...</span>
                </button>
                <a href="{{ route('admin.blogs') }}" wire:navigate class="btn-outline">Cancel</a>
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
