<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->decimal('min_amount', 10, 2)->nullable()->after('value');
            $table->integer('usage_count')->default(0)->after('usage_limit');
        });
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn(['min_amount', 'usage_count']);
        });
    }
};
