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
        $this->call(EventSeeder::class);

        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@anarjo.com'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@anarjo.com',
                'password' => bcrypt('password'),
                'phone' => '01' . fake()->numerify('##########'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('Super Admin');

        User::firstOrCreate(
            ['email' => 'user@anarjo.com'],
            [
                'name' => 'Test User',
                'email' => 'user@anarjo.com',
                'password' => bcrypt('password'),
                'phone' => '01' . fake()->numerify('##########'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        )->assignRole('Author');
    }
}
