<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'view-dashboard',
            'manage-books', 'manage-categories', 'manage-authors',
            'manage-orders', 'manage-users', 'manage-reviews',
            'manage-coupons', 'manage-blogs', 'manage-events',
            'manage-media', 'manage-manuscripts', 'manage-newsletter',
            'manage-seo', 'view-reports', 'manage-settings',
            'manage-roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->givePermissionTo(Permission::all());

        $editor = Role::firstOrCreate(['name' => 'Editor']);
        $editor->syncPermissions([
            'manage-books', 'manage-categories', 'manage-authors',
            'manage-blogs', 'manage-reviews', 'manage-media',
        ]);

        $orderManager = Role::firstOrCreate(['name' => 'Order Manager']);
        $orderManager->syncPermissions([
            'manage-orders', 'manage-coupons',
        ]);

        $author = Role::firstOrCreate(['name' => 'Author']);
        $author->syncPermissions([
            'manage-manuscripts',
        ]);
    }
}
