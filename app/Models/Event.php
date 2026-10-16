<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $fillable = [
        'title', 'slug', 'date', 'venue', 'description', 'image', 'status', 'is_major',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'is_major' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Event $event) {
            if (! $event->slug) {
                $event->slug = static::uniqueSlug($event->title, $event->id);
            }
        });

        static::updating(function (Event $event) {
            if ($event->isDirty('title') && ! $event->isDirty('slug')) {
                $event->slug = static::uniqueSlug($event->title, $event->id);
            }
        });
    }

    protected static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'event';
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
}
