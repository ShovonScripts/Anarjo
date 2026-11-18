<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'logo', 'description', 'status',
    ];

    protected static function booted(): void
    {
        static::creating(function (Brand $brand) {
            if (! $brand->slug) {
                $brand->slug = Str::slug($brand->name);
            }
        });
    }

    public function stationeries(): HasMany
    {
        return $this->hasMany(Stationery::class);
    }
}
