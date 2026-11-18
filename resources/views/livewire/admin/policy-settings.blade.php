<div>
    @section('header', 'Policy Pages Settings')

    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight">Policy Pages</h1>
        <p class="mt-1 text-sm text-neutral-500">Manage the content for Privacy Policy, Terms &amp; Conditions, and Return &amp; Refund Policy pages.</p>
    </div>

    <div wire:loading.remove wire:target="save" x-data="{ show: false }" x-on:settings-saved.window="show = true; setTimeout(() => show = false, 3000)" x-show="show" class="mb-6 px-4 py-3 bg-black text-white text-sm font-semibold uppercase tracking-wider">
        Settings saved successfully.
    </div>

    <form wire:submit="save">
        {{-- Privacy Policy --}}
        <div class="border border-neutral-200 bg-white mb-8">
            <div class="px-8 py-6 border-b border-neutral-200">
                <h2 class="text-lg font-bold tracking-tight">Privacy Policy</h2>
                <p class="mt-1 text-sm text-neutral-500">Displayed at /privacy-policy</p>
            </div>
            <div class="px-8 py-6">
                <div wire:ignore>
                    <div
                        x-ref="privacyEditor"
                        x-init="
                            let quill = new Quill($refs.privacyEditor, {
                                theme: 'snow',
                                modules: {
                                    toolbar: [
                                        ['bold', 'italic', 'underline', 'strike'],
                                        [{ 'header': [2, 3, false] }],
                                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                                        ['blockquote', 'link'],
                                    ]
                                },
                                placeholder: 'Your privacy policy content...'
                            });
                            if ($wire.get('privacy_policy')) {
                                quill.root.innerHTML = $wire.get('privacy_policy');
                            }
                            quill.on('text-change', function () {
                                let html = quill.root.innerHTML;
                                if (html === '<p><br></p>' || html === '<br>') html = '';
                                $wire.set('privacy_policy', html, false);
                            });
                            quill.on('blur', function () {
                                let html = quill.root.innerHTML;
                                if (html === '<p><br></p>' || html === '<br>') html = '';
                                $wire.set('privacy_policy', html);
                            });
                        "
                        style="height: 300px;"
                    >{!! $privacy_policy !!}</div>
                </div>
            </div>
        </div>

        {{-- Terms & Conditions --}}
        <div class="border border-neutral-200 bg-white mb-8">
            <div class="px-8 py-6 border-b border-neutral-200">
                <h2 class="text-lg font-bold tracking-tight">Terms &amp; Conditions</h2>
                <p class="mt-1 text-sm text-neutral-500">Displayed at /terms-conditions</p>
            </div>
            <div class="px-8 py-6">
                <div wire:ignore>
                    <div
                        x-ref="termsEditor"
                        x-init="
                            let quill = new Quill($refs.termsEditor, {
                                theme: 'snow',
                                modules: {
                                    toolbar: [
                                        ['bold', 'italic', 'underline', 'strike'],
                                        [{ 'header': [2, 3, false] }],
                                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                                        ['blockquote', 'link'],
                                    ]
                                },
                                placeholder: 'Your terms and conditions...'
                            });
                            if ($wire.get('terms_conditions')) {
                                quill.root.innerHTML = $wire.get('terms_conditions');
                            }
                            quill.on('text-change', function () {
                                let html = quill.root.innerHTML;
                                if (html === '<p><br></p>' || html === '<br>') html = '';
                                $wire.set('terms_conditions', html, false);
                            });
                            quill.on('blur', function () {
                                let html = quill.root.innerHTML;
                                if (html === '<p><br></p>' || html === '<br>') html = '';
                                $wire.set('terms_conditions', html);
                            });
                        "
                        style="height: 300px;"
                    >{!! $terms_conditions !!}</div>
                </div>
            </div>
        </div>

        {{-- Return & Refund Policy --}}
        <div class="border border-neutral-200 bg-white mb-8">
            <div class="px-8 py-6 border-b border-neutral-200">
                <h2 class="text-lg font-bold tracking-tight">Return &amp; Refund Policy</h2>
                <p class="mt-1 text-sm text-neutral-500">Displayed at /return-refund</p>
            </div>
            <div class="px-8 py-6">
                <div wire:ignore>
                    <div
                        x-ref="returnEditor"
                        x-init="
                            let quill = new Quill($refs.returnEditor, {
                                theme: 'snow',
                                modules: {
                                    toolbar: [
                                        ['bold', 'italic', 'underline', 'strike'],
                                        [{ 'header': [2, 3, false] }],
                                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                                        ['blockquote', 'link'],
                                    ]
                                },
                                placeholder: 'Your return and refund policy...'
                            });
                            if ($wire.get('return_refund')) {
                                quill.root.innerHTML = $wire.get('return_refund');
                            }
                            quill.on('text-change', function () {
                                let html = quill.root.innerHTML;
                                if (html === '<p><br></p>' || html === '<br>') html = '';
                                $wire.set('return_refund', html, false);
                            });
                            quill.on('blur', function () {
                                let html = quill.root.innerHTML;
                                if (html === '<p><br></p>' || html === '<br>') html = '';
                                $wire.set('return_refund', html);
                            });
                        "
                        style="height: 300px;"
                    >{!! $return_refund !!}</div>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn-primary" wire:loading.attr="disabled">Save Changes</button>
            <div wire:loading wire:target="save" class="text-sm text-neutral-500">Saving...</div>
        </div>
    </form>

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
