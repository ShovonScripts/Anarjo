<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);
        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'isbn' => fake()->unique()->isbn13(),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 100, 2000),
            'stock' => fake()->numberBetween(0, 100),
            'language' => 'Bengali',
            'pages' => fake()->numberBetween(50, 500),
            'status' => 'published',
            'featured' => fake()->boolean(),
            'bestseller' => fake()->boolean(),
        ];
    }
}
