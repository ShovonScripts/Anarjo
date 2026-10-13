<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\DB;

class Blog extends Model
{
    protected $fillable = [
        'title', 'slug', 'content', 'featured_image', 'thumbnail',
        'author', 'published_at', 'status',
        'blog_category_id', 'meta_title', 'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function setContentAttribute($value): void
    {
        $this->attributes['content'] = app('purifier')->clean($value);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(BlogTag::class, 'blog_tag_blog');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        $term = trim($term);

        if (DB::getDriverName() === 'mysql' && mb_strlen($term) >= 3) {
            return $query->whereRaw('MATCH(title, content) AGAINST (?)', [$term]);
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('content', 'like', "%{$term}%");
        });
    }
}
