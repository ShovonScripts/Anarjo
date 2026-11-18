<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('register', 'pages.auth.register')
        ->middleware(['throttle:3,60'])
        ->name('register');

    Volt::route('login', 'pages.auth.login')
        ->middleware(['throttle:5,1'])
        ->name('login');

    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->middleware(['throttle:3,60'])
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->middleware(['throttle:3,60'])
        ->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->middleware(['throttle:6,1'])
        ->name('password.confirm');

    Route::post('logout', function () {
        auth()->guard('web')->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/');
    })->name('logout');
});
