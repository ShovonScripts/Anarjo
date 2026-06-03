<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'min_amount',
        'start_date', 'end_date', 'usage_limit', 'usage_count', 'status',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function isValid(): bool
    {
        if ($this->status !== 'active') return false;

        if ($this->start_date && now()->lt($this->start_date)) return false;
        if ($this->end_date && now()->gt($this->end_date)) return false;
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) return false;

        return true;
    }
}
