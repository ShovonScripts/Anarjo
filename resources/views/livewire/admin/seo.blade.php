<div>
    @section('header', 'SEO Manager')

    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight">SEO Manager</h1>
        <p class="mt-1 text-sm text-neutral-500">Optimize your site for search engines and social platforms.</p>
    </div>

    {{-- Tab Navigation --}}
    <div class="border-b border-neutral-200 mb-8">
        <nav class="flex flex-wrap -mb-px">
            <button wire:click="$set('tab', 'general')" class="px-5 py-3 text-sm font-semibold uppercase tracking-wider border-b-2 transition-colors flex items-center gap-2 {{ $tab === 'general' ? 'border-black text-black' : 'border-transparent text-neutral-500 hover:text-black hover:border-neutral-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                General
            </button>
            <button wire:click="$set('tab', 'sitemap')" class="px-5 py-3 text-sm font-semibold uppercase tracking-wider border-b-2 transition-colors flex items-center gap-2 {{ $tab === 'sitemap' ? 'border-black text-black' : 'border-transparent text-neutral-500 hover:text-black hover:border-neutral-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                Sitemap
            </button>
            <button wire:click="$set('tab', 'robots')" class="px-5 py-3 text-sm font-semibold uppercase tracking-wider border-b-2 transition-colors flex items-center gap-2 {{ $tab === 'robots' ? 'border-black text-black' : 'border-transparent text-neutral-500 hover:text-black hover:border-neutral-300' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Robots.txt
            </button>
        </nav>
    </div>

    {{-- ================ GENERAL TAB ================ --}}
    @if ($tab === 'general')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main SEO settings --}}
            <div class="lg:col-span-2 space-y-6">
                <form wire:submit="saveGeneral">
                    <div class="border border-neutral-200 bg-white shadow-sm">
                        <div class="px-8 py-5 border-b border-neutral-200 flex items-center justify-between">
                            <div>
                                <h2 class="text-sm font-bold tracking-tight">Meta Tags</h2>
                                <p class="text-xs text-neutral-500 mt-0.5">Default meta tags for your site.</p>
                            </div>
                            <span class="text-[10px] font-semibold uppercase tracking-widest text-neutral-400">Global</span>
                        </div>
                        <div class="px-8 py-6 space-y-6">
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Default Meta Title</label>
                                <input wire:model="seo_meta_title" type="text" class="input-field w-full" placeholder="Anarjo Publications">
                                <div class="flex justify-between mt-1">
                                    <p class="text-xs text-neutral-400">Used as fallback title across all pages.</p>
                                    <span class="text-xs text-neutral-400">{{ strlen($this->seo_meta_title) }}/255</span>
                                </div>
                                @error('seo_meta_title') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Default Meta Description</label>
                                <textarea wire:model="seo_meta_description" rows="3" class="input-field w-full" placeholder="Bangladesh's leading publication house..."></textarea>
                                <div class="flex justify-between mt-1">
                                    <p class="text-xs text-neutral-400">Appears in search engine results.</p>
                                    <span class="text-xs text-neutral-400">{{ strlen($this->seo_meta_description) }}/500</span>
                                </div>
                                @error('seo_meta_description') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Default OG Image Path</label>
                                <input wire:model="seo_og_image" type="text" class="input-field w-full" placeholder="images/og-default.jpg">
                                <p class="text-xs text-neutral-400 mt-1">Path relative to <code class="text-xs bg-neutral-100 px-1">storage/</code>. Used for social sharing previews.</p>
                                @error('seo_og_image') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Twitter Handle</label>
                                    <input wire:model="seo_twitter_handle" type="text" class="input-field w-full" placeholder="@anarjo">
                                    <p class="text-xs text-neutral-400 mt-1">Your site's Twitter/X username.</p>
                                    @error('seo_twitter_handle') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Analytics & Tracking --}}
                    <div class="border border-neutral-200 bg-white shadow-sm mt-6">
                        <div class="px-8 py-5 border-b border-neutral-200 flex items-center justify-between">
                            <div>
                                <h2 class="text-sm font-bold tracking-tight">Analytics & Tracking</h2>
                                <p class="text-xs text-neutral-500 mt-0.5">Connect your analytics and tracking tools.</p>
                            </div>
                            <span class="text-[10px] font-semibold uppercase tracking-widest text-neutral-400">Scripts</span>
                        </div>
                        <div class="px-8 py-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Google Analytics ID</label>
                                    <input wire:model="seo_google_analytics_id" type="text" class="input-field w-full" placeholder="G-XXXXXXXXXX">
                                    <p class="text-xs text-neutral-400 mt-1">Measurement ID for GA4.</p>
                                    @error('seo_google_analytics_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Google Tag Manager ID</label>
                                    <input wire:model="seo_google_tag_manager_id" type="text" class="input-field w-full" placeholder="GTM-XXXXXXX">
                                    <p class="text-xs text-neutral-400 mt-1">Container ID for GTM.</p>
                                    @error('seo_google_tag_manager_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Facebook Pixel ID</label>
                                    <input wire:model="seo_facebook_pixel_id" type="text" class="input-field w-full" placeholder="1234567890">
                                    <p class="text-xs text-neutral-400 mt-1">Meta Pixel / Facebook Ads.</p>
                                    @error('seo_facebook_pixel_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Site Verification --}}
                    <div class="border border-neutral-200 bg-white shadow-sm mt-6">
                        <div class="px-8 py-5 border-b border-neutral-200 flex items-center justify-between">
                            <div>
                                <h2 class="text-sm font-bold tracking-tight">Site Verification</h2>
                                <p class="text-xs text-neutral-500 mt-0.5">Verify ownership with search engines.</p>
                            </div>
                            <span class="text-[10px] font-semibold uppercase tracking-widest text-neutral-400">Meta Tags</span>
                        </div>
                        <div class="px-8 py-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Bing Site Verification</label>
                                    <input wire:model="seo_bing_verification" type="text" class="input-field w-full" placeholder="XXXXXXXXXXXXXXXX">
                                    <p class="text-xs text-neutral-400 mt-1">Bing Webmaster Tools verification code.</p>
                                    @error('seo_bing_verification') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Yandex Site Verification</label>
                                    <input wire:model="seo_yandex_verification" type="text" class="input-field w-full" placeholder="XXXXXXXXXX">
                                    <p class="text-xs text-neutral-400 mt-1">Yandex Webmaster verification code.</p>
                                    @error('seo_yandex_verification') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-6">
                        <button type="submit" class="btn-primary" wire:loading.attr="disabled">Save Changes</button>
                        <div wire:loading wire:target="saveGeneral" class="text-sm text-neutral-500">Saving...</div>
                    </div>
                </form>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Preview Card --}}
                <div class="border border-neutral-200 bg-white shadow-sm p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <h3 class="text-xs font-semibold uppercase tracking-widest text-neutral-500">Search Preview</h3>
                    </div>
                    <div class="bg-white border border-neutral-200 rounded p-4">
                        <p class="text-xs text-green-700 truncate">{{ url('/') }}</p>
                        <p class="text-sm font-semibold text-blue-700 truncate">{{ $seo_meta_title ?: config('app.name') }}</p>
                        <p class="text-xs text-neutral-600 mt-0.5 line-clamp-2">{{ $seo_meta_description ?: 'No description set.' }}</p>
                    </div>
                </div>

                {{-- Quick Tips --}}
                <div class="border border-neutral-200 bg-white shadow-sm p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h3 class="text-xs font-semibold uppercase tracking-widest text-neutral-500">SEO Tips</h3>
                    </div>
                    <ul class="space-y-2 text-xs text-neutral-600">
                        <li class="flex gap-2">
                            <span class="text-emerald-500 shrink-0">✓</span>
                            <span>Keep titles under 60 characters for best SERP display.</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="text-emerald-500 shrink-0">✓</span>
                            <span>Meta descriptions should be 150-160 characters.</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="{{ strlen($this->seo_og_image) > 0 ? 'text-emerald-500' : 'text-neutral-300' }} shrink-0">✓</span>
                            <span>Add an OG image for better social sharing previews.</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="{{ strlen($this->seo_google_analytics_id) > 0 ? 'text-emerald-500' : 'text-neutral-300' }} shrink-0">✓</span>
                            <span>Connect Google Analytics to track visitor data.</span>
                        </li>
                    </ul>
                </div>

                {{-- Active Integrations --}}
                <div class="border border-neutral-200 bg-white shadow-sm p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <h3 class="text-xs font-semibold uppercase tracking-widest text-neutral-500">Integrations</h3>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between py-2 px-3 {{ $seo_google_analytics_id ? 'bg-emerald-50' : 'bg-neutral-50' }} rounded">
                            <span class="text-xs font-medium {{ $seo_google_analytics_id ? 'text-emerald-700' : 'text-neutral-500' }}">Google Analytics</span>
                            <span class="text-[10px] font-semibold uppercase {{ $seo_google_analytics_id ? 'text-emerald-600' : 'text-neutral-400' }}">{{ $seo_google_analytics_id ? 'Active' : 'Off' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 px-3 {{ $seo_google_tag_manager_id ? 'bg-emerald-50' : 'bg-neutral-50' }} rounded">
                            <span class="text-xs font-medium {{ $seo_google_tag_manager_id ? 'text-emerald-700' : 'text-neutral-500' }}">Tag Manager</span>
                            <span class="text-[10px] font-semibold uppercase {{ $seo_google_tag_manager_id ? 'text-emerald-600' : 'text-neutral-400' }}">{{ $seo_google_tag_manager_id ? 'Active' : 'Off' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 px-3 {{ $seo_facebook_pixel_id ? 'bg-emerald-50' : 'bg-neutral-50' }} rounded">
                            <span class="text-xs font-medium {{ $seo_facebook_pixel_id ? 'text-emerald-700' : 'text-neutral-500' }}">Facebook Pixel</span>
                            <span class="text-[10px] font-semibold uppercase {{ $seo_facebook_pixel_id ? 'text-emerald-600' : 'text-neutral-400' }}">{{ $seo_facebook_pixel_id ? 'Active' : 'Off' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    {{-- ================ SITEMAP TAB ================ --}}
    @elseif ($tab === 'sitemap')
        <form wire:submit="saveSitemap">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Sitemap Settings --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="border border-neutral-200 bg-white shadow-sm">
                        <div class="px-8 py-5 border-b border-neutral-200 flex items-center justify-between">
                            <div>
                                <h2 class="text-sm font-bold tracking-tight">Sitemap Configuration</h2>
                                <p class="text-xs text-neutral-500 mt-0.5">Choose which content types appear in your sitemap.</p>
                            </div>
                            <a href="{{ url('sitemap.xml') }}" target="_blank" class="text-xs font-semibold uppercase tracking-wider text-brand-600 hover:text-brand-700">View Sitemap →</a>
                        </div>
                        <div class="px-8 py-6 space-y-4">
                            <label class="flex items-center justify-between py-4 px-5 border border-neutral-200 rounded cursor-pointer hover:bg-neutral-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-9 h-9 rounded-lg bg-violet-50 flex items-center justify-center text-violet-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold">Books</p>
                                        <p class="text-xs text-neutral-500">{{ $this->sitemapCounts['books'] }} published books</p>
                                    </div>
                                </div>
                                <input type="checkbox" wire:model="sitemap_include_books" class="border-neutral-300 text-black focus:ring-black rounded">
                            </label>

                            <label class="flex items-center justify-between py-4 px-5 border border-neutral-200 rounded cursor-pointer hover:bg-neutral-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-9 h-9 rounded-lg bg-yellow-50 flex items-center justify-center text-yellow-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold">Blog Posts</p>
                                        <p class="text-xs text-neutral-500">{{ $this->sitemapCounts['blogs'] }} published posts</p>
                                    </div>
                                </div>
                                <input type="checkbox" wire:model="sitemap_include_blogs" class="border-neutral-300 text-black focus:ring-black rounded">
                            </label>

                            <label class="flex items-center justify-between py-4 px-5 border border-neutral-200 rounded cursor-pointer hover:bg-neutral-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-9 h-9 rounded-lg bg-orange-50 flex items-center justify-center text-orange-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold">Authors</p>
                                        <p class="text-xs text-neutral-500">{{ $this->sitemapCounts['authors'] }} active authors</p>
                                    </div>
                                </div>
                                <input type="checkbox" wire:model="sitemap_include_authors" class="border-neutral-300 text-black focus:ring-black rounded">
                            </label>

                            <label class="flex items-center justify-between py-4 px-5 border border-neutral-200 rounded cursor-pointer hover:bg-neutral-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold">Categories</p>
                                        <p class="text-xs text-neutral-500">{{ $this->sitemapCounts['categories'] }} categories</p>
                                    </div>
                                </div>
                                <input type="checkbox" wire:model="sitemap_include_categories" class="border-neutral-300 text-black focus:ring-black rounded">
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Sitemap Sidebar --}}
                <div class="space-y-6">
                    <div class="border border-neutral-200 bg-white shadow-sm p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <h3 class="text-xs font-semibold uppercase tracking-widest text-neutral-500">Sitemap Info</h3>
                        </div>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-neutral-500">Total URLs</span>
                                <span class="font-semibold">{{ collect($this->sitemapCounts)->sum() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-neutral-500">Sitemap URL</span>
                                <a href="{{ url('sitemap.xml') }}" target="_blank" class="text-brand-600 hover:text-brand-700 text-xs font-medium">View</a>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-neutral-500">Status</span>
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Active
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="border border-neutral-200 bg-white shadow-sm p-6">
                        <h3 class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-4">What is a Sitemap?</h3>
                        <p class="text-xs text-neutral-600 leading-relaxed">A sitemap helps search engines discover and index your content. It lists all important pages with their priority and update frequency.</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-6">
                <button type="submit" class="btn-primary" wire:loading.attr="disabled">Save Sitemap Settings</button>
                <div wire:loading wire:target="saveSitemap" class="text-sm text-neutral-500">Saving...</div>
            </div>
        </form>

    {{-- ================ ROBOTS.TXT TAB ================ --}}
    @elseif ($tab === 'robots')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <form wire:submit="saveRobots">
                    <div class="border border-neutral-200 bg-white shadow-sm">
                        <div class="px-8 py-5 border-b border-neutral-200 flex items-center justify-between">
                            <div>
                                <h2 class="text-sm font-bold tracking-tight">robots.txt Editor</h2>
                                <p class="text-xs text-neutral-500 mt-0.5">Control how search engines crawl your site.</p>
                            </div>
                            <a href="{{ url('robots.txt') }}" target="_blank" class="text-xs font-semibold uppercase tracking-wider text-brand-600 hover:text-brand-700">View Live →</a>
                        </div>
                        <div class="p-6">
                            <textarea wire:model="robots_txt" rows="16" class="input-field w-full font-mono text-sm" spellcheck="false"></textarea>
                            @error('robots_txt') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-6">
                        <button type="submit" class="btn-primary" wire:loading.attr="disabled">Save robots.txt</button>
                        <button type="button" wire:click="resetRobots" wire:confirm="Reset robots.txt to default?" class="btn-outline">Reset to Default</button>
                        <div wire:loading wire:target="saveRobots" class="text-sm text-neutral-500">Saving...</div>
                    </div>
                </form>
            </div>

            <div class="space-y-6">
                <div class="border border-neutral-200 bg-white shadow-sm p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-4 h-4 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <h3 class="text-xs font-semibold uppercase tracking-widest text-neutral-500">Directives Guide</h3>
                    </div>
                    <div class="space-y-3 text-xs text-neutral-600">
                        <div class="bg-neutral-50 p-3 rounded">
                            <code class="font-semibold text-neutral-800">User-agent: *</code>
                            <p class="mt-0.5">Applies rules to all crawlers.</p>
                        </div>
                        <div class="bg-neutral-50 p-3 rounded">
                            <code class="font-semibold text-neutral-800">Disallow: /admin</code>
                            <p class="mt-0.5">Blocks a path from crawling.</p>
                        </div>
                        <div class="bg-neutral-50 p-3 rounded">
                            <code class="font-semibold text-neutral-800">Allow: /</code>
                            <p class="mt-0.5">Allows crawling of a path.</p>
                        </div>
                        <div class="bg-neutral-50 p-3 rounded">
                            <code class="font-semibold text-neutral-800">Sitemap: /sitemap.xml</code>
                            <p class="mt-0.5">Points to your XML sitemap.</p>
                        </div>
                    </div>
                </div>

                <div class="border border-neutral-200 bg-white shadow-sm p-6">
                    <h3 class="text-xs font-semibold uppercase tracking-widest text-neutral-500 mb-3">Current Status</h3>
                    <div class="flex items-center gap-2 text-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span class="font-medium">Serving at <a href="{{ url('robots.txt') }}" target="_blank" class="text-brand-600 hover:text-brand-700">{{ url('robots.txt') }}</a></span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
