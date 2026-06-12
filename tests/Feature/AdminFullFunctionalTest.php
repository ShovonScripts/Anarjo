<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\User;
use App\Models\Category;
use App\Models\Blog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class AdminFullFunctionalTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_books_page_renders(): void
    {
        // Minimal setup like RouteDebugTest
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        Permission::firstOrCreate(['name' => 'view-dashboard']);
        $role = Role::firstOrCreate(['name' => 'Super Admin']);
        $role->givePermissionTo(Permission::all());
        Role::firstOrCreate(['name' => 'Admin']);

        $admin = User::factory()->create(['email_verified_at' => now()]);
        $admin->assignRole('Super Admin');

        $response = $this->actingAs($admin)->get('/admin/books');
        $this->assertEquals(200, $response->status());
    }
}
