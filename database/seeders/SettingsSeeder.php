<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'Anarjo'],
            ['key' => 'site_tagline', 'value' => "Bangladesh's leading publication house delivering knowledge since inception."],
            ['key' => 'site_description', 'value' => 'Anarjo Publications - Discover books, authors, and literary works from Bangladesh.'],
            ['key' => 'currency_symbol', 'value' => '৳'],
            ['key' => 'currency_code', 'value' => 'BDT'],
            ['key' => 'contact_address', 'value' => 'Dhaka, Bangladesh'],
            ['key' => 'contact_phone', 'value' => ''],
            ['key' => 'contact_email', 'value' => 'info@anarjo.com'],
            ['key' => 'shipping_flat_rate_inside', 'value' => '60'],
            ['key' => 'shipping_flat_rate_outside', 'value' => '100'],
            ['key' => 'shipping_free_threshold', 'value' => '500'],
            ['key' => 'mail_driver', 'value' => 'log'],
            ['key' => 'mail_from_address', 'value' => 'noreply@anarjo.com'],
            ['key' => 'mail_from_name', 'value' => 'Anarjo'],
            ['key' => 'seo_meta_title', 'value' => 'Anarjo Publications - Bangladesh\'s Premier Book Publisher'],
            ['key' => 'seo_meta_description', 'value' => 'Discover a world of knowledge with Anarjo Publications. Explore books, meet authors, and stay updated with literary events.'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }
}
