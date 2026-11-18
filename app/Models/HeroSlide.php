<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'image',
        'link',
        'link_text',
        'sort_order',
        'status',
        'page',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'sort_order' => 'integer',
        ];
    }
}
