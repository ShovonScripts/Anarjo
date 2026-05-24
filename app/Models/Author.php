<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'slug', 'photo', 'bio',
        'facebook', 'linkedin', 'website',
        'royalty_rate', 'status',
    ];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }
}
