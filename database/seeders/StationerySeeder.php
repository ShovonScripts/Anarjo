<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Stationery;
use Illuminate\Database\Seeder;

class StationerySeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Matador', 'description' => 'Premium ballpoint pens & stationery'],
            ['name' => 'Econo', 'description' => 'Quality writing instruments'],
            ['name' => 'Fresh', 'description' => 'Creative stationery for everyone'],
            ['name' => 'Dolphin', 'description' => 'Notebooks, markers & art supplies'],
            ['name' => 'Bond', 'description' => 'Office & school stationery'],
            ['name' => 'Prism', 'description' => 'Colorful stationery products'],
        ];

        foreach ($brands as $data) {
            Brand::factory()->create($data);
        }

        Stationery::factory(30)->create();
    }
}
