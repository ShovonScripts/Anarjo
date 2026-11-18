<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'admin_reply',
        'replied_at',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'replied_at' => 'datetime',
            'read_at' => 'datetime',
        ];
    }
}
