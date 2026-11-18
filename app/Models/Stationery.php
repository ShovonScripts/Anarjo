<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Stationery extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'brand_id', 'description', 'price',
        'sale_price', 'stock', 'image', 'featured', 'status',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
        ];
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        $term = trim($term);

        if (DB::getDriverName() === 'mysql' && mb_strlen($term) >= 3) {
            return $query->whereRaw('MATCH(title, description) AGAINST (?)', [$term]);
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
        });
    }
}
