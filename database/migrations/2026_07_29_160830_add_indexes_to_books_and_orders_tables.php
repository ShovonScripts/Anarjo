<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->index('status');
            $table->index('featured');
            $table->index('bestseller');
            $table->index('new_arrival');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index('payment_status');
            $table->index('delivery_status');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['featured']);
            $table->dropIndex(['bestseller']);
            $table->dropIndex(['new_arrival']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['payment_status']);
            $table->dropIndex(['delivery_status']);
        });
    }
};
