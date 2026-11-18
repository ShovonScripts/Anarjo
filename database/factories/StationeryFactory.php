<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Stationery;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StationeryFactory extends Factory
{
    protected $model = Stationery::class;

    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'brand_id' => Brand::factory(),
            'description' => fake()->paragraph(),
            'price' => fake()->numberBetween(50, 5000),
            'sale_price' => fake()->boolean(30) ? fake()->numberBetween(30, 4000) : null,
            'stock' => fake()->numberBetween(0, 200),
            'image' => null,
            'featured' => fake()->boolean(20),
            'status' => 'published',
        ];
    }
}
