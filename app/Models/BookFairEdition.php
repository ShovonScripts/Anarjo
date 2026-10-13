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
                $edition->slug = Str::slug($edition->title);
            }
        });
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
