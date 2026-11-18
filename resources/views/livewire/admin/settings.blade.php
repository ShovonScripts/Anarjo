<div>
    @section('header', 'Settings')
    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight">Settings</h1>
        <p class="mt-1 text-sm text-neutral-500">Manage your site configuration, payment gateways, and more.</p>
    </div>

    {{-- Tab Navigation --}}
    <div class="border-b border-neutral-200 mb-8">
        <nav class="flex flex-wrap -mb-px">
            <button wire:click="$set('tab', 'general')" class="px-5 py-3 text-sm font-semibold uppercase tracking-wider border-b-2 transition-colors {{ $tab === 'general' ? 'border-black text-black' : 'border-transparent text-neutral-500 hover:text-black hover:border-neutral-300' }}">
                General
            </button>
            <button wire:click="$set('tab', 'logo')" class="px-5 py-3 text-sm font-semibold uppercase tracking-wider border-b-2 transition-colors {{ $tab === 'logo' ? 'border-black text-black' : 'border-transparent text-neutral-500 hover:text-black hover:border-neutral-300' }}">
                Logo & Favicon
            </button>
            <button wire:click="$set('tab', 'contact')" class="px-5 py-3 text-sm font-semibold uppercase tracking-wider border-b-2 transition-colors {{ $tab === 'contact' ? 'border-black text-black' : 'border-transparent text-neutral-500 hover:text-black hover:border-neutral-300' }}">
                Contact
            </button>
            <button wire:click="$set('tab', 'email')" class="px-5 py-3 text-sm font-semibold uppercase tracking-wider border-b-2 transition-colors {{ $tab === 'email' ? 'border-black text-black' : 'border-transparent text-neutral-500 hover:text-black hover:border-neutral-300' }}">
                Email
            </button>
            <button wire:click="$set('tab', 'payment')" class="px-5 py-3 text-sm font-semibold uppercase tracking-wider border-b-2 transition-colors {{ $tab === 'payment' ? 'border-black text-black' : 'border-transparent text-neutral-500 hover:text-black hover:border-neutral-300' }}">
                Payment
            </button>
            <button wire:click="$set('tab', 'shipping')" class="px-5 py-3 text-sm font-semibold uppercase tracking-wider border-b-2 transition-colors {{ $tab === 'shipping' ? 'border-black text-black' : 'border-transparent text-neutral-500 hover:text-black hover:border-neutral-300' }}">
                Shipping
            </button>
        </nav>
    </div>

    {{-- Saved Message --}}
    <div wire:loading.remove wire:target="save" x-data="{ show: false }" x-on:settings-saved.window="show = true; setTimeout(() => show = false, 3000)" x-show="show" class="mb-6 px-4 py-3 bg-black text-white text-sm font-semibold uppercase tracking-wider">
        Settings saved successfully.
    </div>

    {{-- General Tab --}}
    @if ($tab === 'general')
        <div class="border border-neutral-200 bg-white">
            <div class="px-8 py-6 border-b border-neutral-200">
                <h2 class="text-lg font-bold tracking-tight">General Settings</h2>
                <p class="mt-1 text-sm text-neutral-500">Basic site information and branding.</p>
            </div>
            <form wire:submit="save" class="px-8 py-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="site_name" value="Site Name" />
                        <x-text-input wire:model="site_name" id="site_name" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label for="currency_symbol" value="Currency Symbol" />
                        <x-text-input wire:model="currency_symbol" id="currency_symbol" class="mt-1 block w-full" placeholder="৳" />
                    </div>
                    <div>
                        <x-input-label for="site_tagline" value="Tagline" />
                        <x-text-input wire:model="site_tagline" id="site_tagline" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label for="currency_code" value="Currency Code" />
                        <x-text-input wire:model="currency_code" id="currency_code" class="mt-1 block w-full" placeholder="BDT" />
                    </div>
                </div>
                <div>
                    <x-input-label for="site_description" value="Site Description" />
                    <textarea wire:model="site_description" id="site_description" rows="3" class="input-field mt-1 block w-full"></textarea>
                    <p class="mt-1 text-xs text-neutral-400">Used for SEO meta description fallback.</p>
                </div>
                <div class="flex items-center gap-4 pt-2">
                    <button type="submit" class="btn-primary" wire:loading.attr="disabled">Save Changes</button>
                    <div wire:loading wire:target="save" class="text-sm text-neutral-500">Saving...</div>
                </div>
            </form>
        </div>

    {{-- Logo & Favicon Tab --}}
    @elseif ($tab === 'logo')
        <div class="border border-neutral-200 bg-white">
            <div class="px-8 py-6 border-b border-neutral-200">
                <h2 class="text-lg font-bold tracking-tight">Logo & Favicon</h2>
                <p class="mt-1 text-sm text-neutral-500">Upload your brand logos and favicon.</p>
            </div>
            <form wire:submit="save" class="px-8 py-6 space-y-8">
                {{-- Header Logo --}}
                <div>
                    <x-input-label value="Header Logo" />
                    <p class="text-xs text-neutral-400 mb-3">Appears in the navigation bar. Recommended: 200x60px PNG.</p>
                    @if ($header_logo_path)
                        <div class="flex items-center gap-4 mb-4">
                            <img src="{{ asset('storage/' . $header_logo_path) }}" class="h-10 border border-neutral-200">
                            <button type="button" wire:click="removeHeaderLogo" class="text-sm text-brand-600 hover:text-brand-700">Remove</button>
                        </div>
                    @endif
                    <input type="file" wire:model="header_logo" accept="image/png,image/jpeg,image/svg+xml" class="block w-full text-sm text-neutral-500 file:mr-4 file:py-2 file:px-4 file:border file:border-neutral-300 file:text-sm file:font-semibold file:bg-white file:text-black hover:file:bg-neutral-50">
                    <x-input-error :messages="$errors->get('header_logo')" class="mt-2" />
                </div>

                {{-- Footer Logo --}}
                <div>
                    <x-input-label value="Footer Logo" />
                    <p class="text-xs text-neutral-400 mb-3">Appears in the footer. Recommended: 200x60px PNG.</p>
                    @if ($footer_logo_path)
                        <div class="flex items-center gap-4 mb-4">
                            <img src="{{ asset('storage/' . $footer_logo_path) }}" class="h-10 border border-neutral-200">
                            <button type="button" wire:click="removeFooterLogo" class="text-sm text-brand-600 hover:text-brand-700">Remove</button>
                        </div>
                    @endif
                    <input type="file" wire:model="footer_logo" accept="image/png,image/jpeg,image/svg+xml" class="block w-full text-sm text-neutral-500 file:mr-4 file:py-2 file:px-4 file:border file:border-neutral-300 file:text-sm file:font-semibold file:bg-white file:text-black hover:file:bg-neutral-50">
                    <x-input-error :messages="$errors->get('footer_logo')" class="mt-2" />
                </div>

                {{-- Favicon --}}
                <div>
                    <x-input-label value="Favicon" />
                    <p class="text-xs text-neutral-400 mb-3">Browser tab icon. Recommended: 32x32px ICO or PNG.</p>
                    @if ($favicon_path)
                        <div class="flex items-center gap-4 mb-4">
                            <img src="{{ asset('storage/' . $favicon_path) }}" class="h-8 w-8 border border-neutral-200">
                            <button type="button" wire:click="removeFavicon" class="text-sm text-brand-600 hover:text-brand-700">Remove</button>
                        </div>
                    @endif
                    <input type="file" wire:model="favicon" accept="image/png,image/x-icon,image/svg+xml" class="block w-full text-sm text-neutral-500 file:mr-4 file:py-2 file:px-4 file:border file:border-neutral-300 file:text-sm file:font-semibold file:bg-white file:text-black hover:file:bg-neutral-50">
                    <x-input-error :messages="$errors->get('favicon')" class="mt-2" />
                </div>

                <div class="flex items-center gap-4 pt-2">
                    <button type="submit" class="btn-primary" wire:loading.attr="disabled">Save Changes</button>
                    <div wire:loading wire:target="save" class="text-sm text-neutral-500">Saving...</div>
                </div>
            </form>
        </div>

    {{-- Contact Tab --}}
    @elseif ($tab === 'contact')
        <div class="border border-neutral-200 bg-white">
            <div class="px-8 py-6 border-b border-neutral-200">
                <h2 class="text-lg font-bold tracking-tight">Contact & Social</h2>
                <p class="mt-1 text-sm text-neutral-500">Contact information and social media links for your site.</p>
            </div>
            <form wire:submit="save" class="px-8 py-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="contact_email" value="Contact Email" />
                        <x-text-input wire:model="contact_email" id="contact_email" type="email" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label for="contact_phone" value="Contact Phone" />
                        <x-text-input wire:model="contact_phone" id="contact_phone" class="mt-1 block w-full" />
                    </div>
                </div>
                <div>
                    <x-input-label for="contact_address" value="Address" />
                    <textarea wire:model="contact_address" id="contact_address" rows="3" class="input-field mt-1 block w-full"></textarea>
                </div>
                <div class="border-t border-neutral-200 pt-6">
                    <h3 class="text-sm font-bold uppercase tracking-wider mb-4">Social Links</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="social_facebook" value="Facebook" />
                            <x-text-input wire:model="social_facebook" id="social_facebook" class="mt-1 block w-full" placeholder="https://facebook.com/..." />
                        </div>
                        <div>
                            <x-input-label for="social_twitter" value="Twitter / X" />
                            <x-text-input wire:model="social_twitter" id="social_twitter" class="mt-1 block w-full" placeholder="https://twitter.com/..." />
                        </div>
                        <div>
                            <x-input-label for="social_instagram" value="Instagram" />
                            <x-text-input wire:model="social_instagram" id="social_instagram" class="mt-1 block w-full" placeholder="https://instagram.com/..." />
                        </div>
                        <div>
                            <x-input-label for="social_linkedin" value="LinkedIn" />
                            <x-text-input wire:model="social_linkedin" id="social_linkedin" class="mt-1 block w-full" placeholder="https://linkedin.com/..." />
                        </div>
                        <div>
                            <x-input-label for="social_youtube" value="YouTube" />
                            <x-text-input wire:model="social_youtube" id="social_youtube" class="mt-1 block w-full" placeholder="https://youtube.com/..." />
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-4 pt-2">
                    <button type="submit" class="btn-primary" wire:loading.attr="disabled">Save Changes</button>
                    <div wire:loading wire:target="save" class="text-sm text-neutral-500">Saving...</div>
                </div>
            </form>
        </div>

    {{-- Email Tab --}}
    @elseif ($tab === 'email')
        <div class="border border-neutral-200 bg-white">
            <div class="px-8 py-6 border-b border-neutral-200">
                <h2 class="text-lg font-bold tracking-tight">Email Settings</h2>
                <p class="mt-1 text-sm text-neutral-500">Configure SMTP for transactional emails (order confirmations, etc.).</p>
            </div>
            <form wire:submit="save" class="px-8 py-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <x-input-label for="mail_driver" value="Mail Driver" />
                        <select wire:model="mail_driver" id="mail_driver" class="input-field mt-1 block w-full">
                            <option value="smtp">SMTP</option>
                            <option value="sendmail">Sendmail</option>
                            <option value="mail">PHP Mail</option>
                            <option value="log">Log (dev)</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label for="mail_host" value="SMTP Host" />
                        <x-text-input wire:model="mail_host" id="mail_host" class="mt-1 block w-full" placeholder="smtp.gmail.com" />
                    </div>
                    <div>
                        <x-input-label for="mail_port" value="SMTP Port" />
                        <x-text-input wire:model="mail_port" id="mail_port" class="mt-1 block w-full" placeholder="587" />
                    </div>
                    <div>
                        <x-input-label for="mail_username" value="SMTP Username" />
                        <x-text-input id="mail_username" class="mt-1 block w-full" disabled value="{{ config('mail.mailers.smtp.username') }}" />
                    </div>
                    <div>
                        <x-input-label for="mail_password" value="SMTP Password" />
                        <input id="mail_password" type="password" class="input-field mt-1 block w-full" disabled value="********" />
                        <p class="text-xs text-neutral-500 mt-1">Credentials are configured in <code>.env</code> and cannot be changed here.</p>
                    </div>
                    <div>
                        <x-input-label for="mail_encryption" value="Encryption" />
                        <select wire:model="mail_encryption" id="mail_encryption" class="input-field mt-1 block w-full">
                            <option value="tls">TLS</option>
                            <option value="ssl">SSL</option>
                            <option value="">None</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="mail_from_address" value="From Address" />
                        <x-text-input wire:model="mail_from_address" id="mail_from_address" class="mt-1 block w-full" placeholder="noreply@anarjo.com" />
                    </div>
                    <div>
                        <x-input-label for="mail_from_name" value="From Name" />
                        <x-text-input wire:model="mail_from_name" id="mail_from_name" class="mt-1 block w-full" placeholder="Anarjo" />
                    </div>
                </div>
                <div class="flex items-center gap-4 pt-2">
                    <button type="submit" class="btn-primary" wire:loading.attr="disabled">Save Changes</button>
                    <div wire:loading wire:target="save" class="text-sm text-neutral-500">Saving...</div>
                </div>
            </form>
        </div>

    {{-- Payment Tab --}}
    @elseif ($tab === 'payment')
        <div class="border border-neutral-200 bg-white">
            <div class="px-8 py-6 border-b border-neutral-200">
                <h2 class="text-lg font-bold tracking-tight">Payment Settings</h2>
                <p class="mt-1 text-sm text-neutral-500">Configure payment gateways for your store.</p>
            </div>
            <form wire:submit="save" class="px-8 py-6 space-y-8">
                {{-- Payment Method Toggles --}}
                <div class="border border-neutral-200 p-6">
                    <h3 class="text-sm font-bold uppercase tracking-wider mb-1">Active Payment Methods</h3>
                    <p class="text-xs text-neutral-400 mb-4">Enable or disable payment methods on the checkout page.</p>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" wire:model="pm_cod" class="border-neutral-300 text-black focus:ring-black">
                            <span class="text-sm font-medium">Cash on Delivery</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" wire:model="pm_bkash" class="border-neutral-300 text-black focus:ring-black">
                            <span class="text-sm font-medium">bKash (Merchant API)</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" wire:model="pm_sslcommerz" class="border-neutral-300 text-black focus:ring-black">
                            <span class="text-sm font-medium">SSLCommerz</span>
                        </label>
                    </div>
                </div>

                {{-- bKash Merchant API --}}
                <div class="border border-neutral-200 p-6">
                    <h3 class="text-sm font-bold uppercase tracking-wider mb-1">bKash Merchant API</h3>
                    <p class="text-xs text-neutral-400 mb-4">Automatic payment processing via bKash Tokenized Checkout.</p>
                    <div class="bg-neutral-50 border border-neutral-200 px-4 py-3 mb-4 text-sm text-neutral-600">
                        <strong class="text-neutral-900">Credentials are managed via environment variables.</strong>
                        Set <code class="text-brand-700 bg-brand-50 px-1.5 py-0.5 text-xs font-mono">BKASH_APP_KEY</code>,
                        <code class="text-brand-700 bg-brand-50 px-1.5 py-0.5 text-xs font-mono">BKASH_APP_SECRET</code>,
                        <code class="text-brand-700 bg-brand-50 px-1.5 py-0.5 text-xs font-mono">BKASH_USERNAME</code>, and
                        <code class="text-brand-700 bg-brand-50 px-1.5 py-0.5 text-xs font-mono">BKASH_PASSWORD</code>
                        in your <code class="text-brand-700 bg-brand-50 px-1.5 py-0.5 text-xs font-mono">.env</code> file.
                    </div>
                    <div>
                        <label class="inline-flex items-center gap-3">
                            <input type="checkbox" wire:model="payment_bkash_sandbox" class="border-neutral-300 text-black focus:ring-black">
                            <span class="text-sm font-medium">Sandbox Mode (Test)</span>
                        </label>
                    </div>
                </div>

                {{-- SSLCommerz --}}
                <div class="border border-neutral-200 p-6">
                    <h3 class="text-sm font-bold uppercase tracking-wider mb-1">SSLCommerz</h3>
                    <p class="text-xs text-neutral-400 mb-4">Automatic payment processing via SSLCommerz.</p>
                    <div class="bg-neutral-50 border border-neutral-200 px-4 py-3 mb-4 text-sm text-neutral-600">
                        <strong class="text-neutral-900">Credentials are managed via environment variables.</strong>
                        Set <code class="text-brand-700 bg-brand-50 px-1.5 py-0.5 text-xs font-mono">SSLCOMMERZ_STORE_ID</code> and
                        <code class="text-brand-700 bg-brand-50 px-1.5 py-0.5 text-xs font-mono">SSLCOMMERZ_STORE_PASSWD</code>
                        in your <code class="text-brand-700 bg-brand-50 px-1.5 py-0.5 text-xs font-mono">.env</code> file.
                    </div>
                    <div>
                        <label class="inline-flex items-center gap-3">
                            <input type="checkbox" wire:model="payment_sslcommerz_sandbox" class="border-neutral-300 text-black focus:ring-black">
                            <span class="text-sm font-medium">Sandbox Mode (Test)</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-2">
                    <button type="submit" class="btn-primary" wire:loading.attr="disabled">Save Changes</button>
                    <div wire:loading wire:target="save" class="text-sm text-neutral-500">Saving...</div>
                </div>
            </form>
        </div>

    {{-- Shipping Tab --}}
    @elseif ($tab === 'shipping')
        <div class="border border-neutral-200 bg-white">
            <div class="px-8 py-6 border-b border-neutral-200">
                <h2 class="text-lg font-bold tracking-tight">Shipping Settings</h2>
                <p class="mt-1 text-sm text-neutral-500">Configure shipping rates and thresholds.</p>
            </div>
            <form wire:submit="save" class="px-8 py-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-2xl">
                    <div>
                        <x-input-label for="shipping_flat_rate_inside" value="Inside Dhaka" />
                        <x-text-input wire:model="shipping_flat_rate_inside" id="shipping_flat_rate_inside" class="mt-1 block w-full" placeholder="60" />
                    </div>
                    <div>
                        <x-input-label for="shipping_flat_rate_outside" value="Outside Dhaka" />
                        <x-text-input wire:model="shipping_flat_rate_outside" id="shipping_flat_rate_outside" class="mt-1 block w-full" placeholder="100" />
                    </div>
                    <div>
                        <x-input-label for="shipping_free_threshold" value="Free Shipping Threshold" />
                        <x-text-input wire:model="shipping_free_threshold" id="shipping_free_threshold" class="mt-1 block w-full" placeholder="0" />
                        <p class="mt-1 text-xs text-neutral-400">Orders above this get free shipping.</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 pt-2">
                    <button type="submit" class="btn-primary" wire:loading.attr="disabled">Save Changes</button>
                    <div wire:loading wire:target="save" class="text-sm text-neutral-500">Saving...</div>
                </div>
            </form>
        </div>

    @endif
</div>
