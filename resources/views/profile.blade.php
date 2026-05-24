<x-app-layout>
    <div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="mb-10">
            <h1 class="text-3xl font-bold tracking-tight">Account Settings</h1>
            <p class="mt-2 text-sm text-neutral-500">Manage your profile, security, and account preferences.</p>
        </div>

        <div x-data="{ tab: window.location.hash.replace('#', '') || 'profile' }" class="flex flex-col md:flex-row gap-8">
            {{-- Sidebar --}}
            <nav class="md:w-56 shrink-0">
                <div class="border border-neutral-200 divide-y divide-neutral-200">
                    <button @click="tab = 'profile'; window.location.hash = 'profile'" :class="tab === 'profile' ? 'bg-neutral-100 text-black' : 'text-neutral-600 hover:text-black hover:bg-neutral-50'" class="w-full text-left px-5 py-4 text-sm font-semibold uppercase tracking-wider transition-colors">
                        Profile
                    </button>
                    <button @click="tab = 'password'; window.location.hash = 'password'" :class="tab === 'password' ? 'bg-neutral-100 text-black' : 'text-neutral-600 hover:text-black hover:bg-neutral-50'" class="w-full text-left px-5 py-4 text-sm font-semibold uppercase tracking-wider transition-colors">
                        Password
                    </button>
                    <button @click="tab = 'danger'; window.location.hash = 'danger'" :class="tab === 'danger' ? 'bg-neutral-100 text-black' : 'text-neutral-600 hover:text-black hover:bg-neutral-50'" class="w-full text-left px-5 py-4 text-sm font-semibold uppercase tracking-wider text-brand-600 transition-colors">
                        Danger Zone
                    </button>
                </div>
            </nav>

            {{-- Panels --}}
            <div class="flex-1 min-w-0">
                <div x-show="tab === 'profile'" x-cloak>
                    <livewire:profile.update-profile-information-form />
                </div>
                <div x-show="tab === 'password'" x-cloak>
                    <livewire:profile.update-password-form />
                </div>
                <div x-show="tab === 'danger'" x-cloak>
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
