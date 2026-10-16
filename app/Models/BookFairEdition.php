<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class BookFairEdition extends Model
{
    protected $fillable = [
        'title', 'slug', 'tagline', 'start_date', 'end_date',
        'venue', 'image', 'is_current', 'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_current' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (BookFairEdition $edition) {
            if (! $edition->slug) {
                $edition->slug = static::uniqueSlug($edition->title, $edition->id);
            }
        });

        static::updating(function (BookFairEdition $edition) {
            if ($edition->isDirty('title') && ! $edition->isDirty('slug')) {
                $edition->slug = static::uniqueSlug($edition->title, $edition->id);
            }
        });
    }

    protected static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'edition';
        $slug = $base;
        $i = 1;

        while (static::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_book_fair_edition');
    }
}
