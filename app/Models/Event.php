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
            if (!$event->slug) {
                $event->slug = Str::slug($event->title);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
