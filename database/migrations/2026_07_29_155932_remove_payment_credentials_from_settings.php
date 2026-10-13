<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('settings')->whereIn('key', [
            'payment_bkash_app_key',
            'payment_bkash_app_secret',
            'payment_bkash_username',
            'payment_bkash_password',
            'payment_sslcommerz_store_id',
            'payment_sslcommerz_store_passwd',
        ])->delete();

        Cache::forget('settings');
    }

    public function down(): void
    {
        // Values were removed from DB — cannot restore lost secrets.
    }
};
