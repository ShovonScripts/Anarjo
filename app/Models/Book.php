<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'slug', 'isbn', 'author_id', 'category_id', 'publisher_id',
        'description', 'excerpt', 'price', 'sale_price', 'stock',
        'cover_image', 'preview_pdf', 'language', 'pages', 'edition',
        'publish_date', 'weight', 'featured', 'bestseller', 'new_arrival',
        'pre_order', 'status', 'seo_title', 'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'bestseller' => 'boolean',
            'new_arrival' => 'boolean',
            'pre_order' => 'boolean',
            'publish_date' => 'date',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'publisher_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
