<?php

namespace App\Providers;

use App\Services\SettingsService;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SettingsService::class, function () {
            return new SettingsService;
        });
    }

    public function boot(): void
    {
        $this->app->booted(function () {
            $this->disablePublicStorageUploads();
        });
    }

    private function disablePublicStorageUploads(): void
    {
        Route::put('storage/{path}', function () {
            abort(404);
        })->where('path', '.*');
    }
}
