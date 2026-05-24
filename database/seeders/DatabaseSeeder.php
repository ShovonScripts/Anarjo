<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(BlogSeeder::class);

        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@anarjo.com',
        ]);
        $superAdmin->assignRole('Super Admin');

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@anarjo.com',
        ]);
        $user->assignRole('Author');
    }
}
