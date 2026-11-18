<?php

use App\Services\SettingsService;

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        return app(SettingsService::class)->get($key, $default);
    }
}

if (! function_exists('stationeryEnabled')) {
    function stationeryEnabled(): bool
    {
        return (bool) setting('stationery_enabled', true);
    }
}
