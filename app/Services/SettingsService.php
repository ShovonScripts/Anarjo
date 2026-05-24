<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\QueryException;

class SettingsService
{
    protected array $settings = [];

    public function __construct()
    {
        $this->load();
    }

    protected function load(): void
    {
        try {
            $this->settings = Cache::rememberForever('site_settings', function () {
                return Setting::pluck('value', 'key')->toArray();
            });
        } catch (QueryException) {
            $this->settings = [];
        }
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->settings[$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        try {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            $this->settings[$key] = $value;
            Cache::forget('site_settings');
        } catch (QueryException) {
            //
        }
    }

    public function setMultiple(array $data): void
    {
        try {
            foreach ($data as $key => $value) {
                Setting::updateOrCreate(['key' => $key], ['value' => $value]);
                $this->settings[$key] = $value;
            }
            Cache::forget('site_settings');
        } catch (QueryException) {
            //
        }
    }

    public function all(): array
    {
        return $this->settings;
    }

    public function clearCache(): void
    {
        Cache::forget('site_settings');
        $this->load();
    }
}
