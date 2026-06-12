<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->dropColumn(['facebook', 'linkedin', 'website', 'royalty_rate']);
        });
    }

    public function down(): void
    {
        Schema::table('authors', function (Blueprint $table) {
            $table->string('facebook')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('website')->nullable();
            $table->decimal('royalty_rate', 5, 2)->default(0);
        });
    }
};
