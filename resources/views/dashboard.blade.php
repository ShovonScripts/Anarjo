<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold tracking-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-neutral-200 p-8">
                <p class="text-neutral-600">{{ __("You're logged in!") }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
