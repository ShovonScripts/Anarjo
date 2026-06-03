<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = [
        'name', 'path', 'type', 'size', 'disk',
    ];

    public function url(): string
    {
        return asset('storage/' . $this->path);
    }

    public function thumbnail(): string
    {
        return str_starts_with($this->type, 'image')
            ? $this->url()
            : asset('images/file-icon.png');
    }

    public function sizeForHumans(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 1) . ' ' . $units[$i];
    }

    public function isImage(): bool
    {
        return str_starts_with($this->type, 'image');
    }
}
