<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        Schema::table('books', function (Blueprint $table) {
            $table->fullText(['title', 'description'], 'books_search');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->fullText(['title', 'content'], 'blogs_search');
        });

        Schema::table('stationeries', function (Blueprint $table) {
            $table->fullText(['title', 'description'], 'stationeries_search');
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        Schema::table('books', function (Blueprint $table) {
            $table->dropIndex('books_search');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropIndex('blogs_search');
        });

        Schema::table('stationeries', function (Blueprint $table) {
            $table->dropIndex('stationeries_search');
        });
    }
};
