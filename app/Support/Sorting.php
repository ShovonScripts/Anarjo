<?php

namespace App\Support;

class Sorting
{
    public static function apply($query, string $sort): void
    {
        match ($sort) {
            'oldest' => $query->oldest(),
            'price-asc' => $query->orderBy('price', 'asc'),
            'price-desc' => $query->orderBy('price', 'desc'),
            'discount' => $query->orderByRaw('(COALESCE(price, 0) - COALESCE(sale_price, price)) desc'),
            'title-asc' => $query->orderBy('title', 'asc'),
            'title-desc' => $query->orderBy('title', 'desc'),
            'most-visited' => $query->orderBy('views', 'desc'),
            default => $query->latest(),
        };
    }
}
