<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manuscript extends Model
{
    protected $fillable = [
        'author_name', 'email', 'phone', 'book_title',
        'genre', 'file', 'status', 'remarks',
    ];
}
