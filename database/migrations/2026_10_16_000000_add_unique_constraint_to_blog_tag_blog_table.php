<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $duplicates = DB::table('blog_tag_blog')
            ->selectRaw('MIN(id) as keep_id, blog_id, blog_tag_id')
            ->groupBy('blog_id', 'blog_tag_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            DB::table('blog_tag_blog')
                ->where('blog_id', $duplicate->blog_id)
                ->where('blog_tag_id', $duplicate->blog_tag_id)
                ->where('id', '!=', $duplicate->keep_id)
                ->delete();
        }

        Schema::table('blog_tag_blog', function (Blueprint $table) {
            $table->unique(['blog_id', 'blog_tag_id']);
        });
    }

    public function down(): void
    {
        Schema::table('blog_tag_blog', function (Blueprint $table) {
            $table->dropUnique(['blog_id', 'blog_tag_id']);
        });
    }
};
