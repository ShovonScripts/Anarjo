<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AuthorFactory extends Factory
{
    protected $model = Author::class;

    public function definition(): array
    {
        $name = fake()->name();
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'bio' => fake()->paragraph(),
            'royalty_rate' => fake()->randomFloat(2, 5, 20),
            'status' => 'active',
        ];
    }
}
