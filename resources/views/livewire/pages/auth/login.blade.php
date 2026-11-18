<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $user = auth()->user();
        $redirect = $user?->hasRole(['Super Admin', 'Admin'])
            ? route('admin.dashboard', absolute: false)
            : route('home', absolute: false);

        $this->redirectIntended(default: $redirect, navigate: true);
    }
}; ?>

<div>
    <h1 class="text-2xl font-extrabold tracking-tight mb-1">Welcome Back</h1>
    <p class="text-sm text-neutral-500 mb-8">Sign in to your account to continue.</p>

    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form wire:submit="login" class="space-y-5">
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full" type="email" name="email" required autofocus autocomplete="username" placeholder="your@email.com" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember" class="inline-flex items-center gap-2 cursor-pointer">
                <input wire:model="form.remember" id="remember" type="checkbox" class="border-neutral-300 text-black focus:ring-black rounded">
                <span class="text-sm text-neutral-600">{{ __('Remember me') }}</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-sm font-semibold text-brand-600 hover:text-black transition-colors" href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <button type="submit" class="btn-primary w-full text-center">
            Sign In
        </button>

        <p class="text-center text-sm text-neutral-500">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-semibold text-black hover:text-brand-600 transition-colors" wire:navigate>Create one</a>
        </p>
    </form>
</div>
