<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class AdminFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seedRolesAndPermissions();
    }

    private function seedRolesAndPermissions(): void
    {
        app()->make(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'view-dashboard', 'manage-books', 'manage-categories', 'manage-authors',
            'manage-orders', 'manage-users', 'manage-reviews', 'manage-coupons',
            'manage-blogs', 'manage-events', 'manage-media', 'manage-manuscripts',
            'manage-newsletter', 'manage-seo', 'view-reports', 'manage-settings',
            'manage-roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $role = Role::firstOrCreate(['name' => 'Super Admin']);
        $role->givePermissionTo(Permission::all());

        Role::firstOrCreate(['name' => 'Author']);
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Author');

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    public function test_admin_can_view_books_page(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $response = $this->actingAs($admin)->get(route('admin.books'));

        $response->assertStatus(200);
    }

    public function test_admin_can_view_authors_page(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $response = $this->actingAs($admin)->get(route('admin.authors'));

        $response->assertStatus(200);
    }

    public function test_admin_can_view_categories_page(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $response = $this->actingAs($admin)->get(route('admin.categories'));

        $response->assertStatus(200);
    }

    public function test_admin_can_view_orders_page(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $response = $this->actingAs($admin)->get(route('admin.orders'));

        $response->assertStatus(200);
    }

    public function test_admin_can_view_users_page(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $response = $this->actingAs($admin)->get(route('admin.users'));

        $response->assertStatus(200);
    }

    public function test_admin_can_view_blogs_page(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $response = $this->actingAs($admin)->get(route('admin.blogs'));

        $response->assertStatus(200);
    }

    public function test_admin_can_view_events_page(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $response = $this->actingAs($admin)->get(route('admin.events'));

        $response->assertStatus(200);
    }

    public function test_admin_can_view_reviews_page(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $response = $this->actingAs($admin)->get(route('admin.reviews'));

        $response->assertStatus(200);
    }

    public function test_admin_can_view_coupons_page(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $response = $this->actingAs($admin)->get(route('admin.coupons'));

        $response->assertStatus(200);
    }

    public function test_admin_can_view_manuscripts_page(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $response = $this->actingAs($admin)->get(route('admin.manuscripts'));

        $response->assertStatus(200);
    }

    public function test_admin_can_view_media_page(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $response = $this->actingAs($admin)->get(route('admin.media'));

        $response->assertStatus(200);
    }

    public function test_admin_can_view_settings_page(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $response = $this->actingAs($admin)->get(route('admin.settings'));

        $response->assertStatus(200);
    }

    public function test_admin_can_view_blog_categories_page(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $response = $this->actingAs($admin)->get(route('admin.blog-categories'));

        $response->assertStatus(200);
    }

    public function test_admin_can_view_blog_tags_page(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $response = $this->actingAs($admin)->get(route('admin.blog-tags'));

        $response->assertStatus(200);
    }

    public function test_admin_can_view_blog_create_page(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $response = $this->actingAs($admin)->get(route('admin.blogs.create'));

        $response->assertStatus(200);
    }

    public function test_admin_can_view_newsletter_page(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $response = $this->actingAs($admin)->get(route('admin.newsletter'));

        $response->assertStatus(200);
    }

    public function test_admin_can_view_seo_page(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $response = $this->actingAs($admin)->get(route('admin.seo'));

        $response->assertStatus(200);
    }

    public function test_admin_can_view_reports_page(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $response = $this->actingAs($admin)->get(route('admin.reports'));

        $response->assertStatus(200);
    }

    public function test_admin_can_view_order_detail(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');
        $author = Author::factory()->create();
        $book = Book::factory()->create([
            'author_id' => $author->id,
            'status' => 'published',
        ]);
        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'invoice_no' => 'INV-TEST-001',
            'subtotal' => 100,
            'shipping' => 60,
            'discount' => 0,
            'total' => 160,
            'payment_method' => 'cod',
            'payment_status' => 'pending',
            'delivery_status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.orders.show', $order));

        $response->assertStatus(200);
    }

    public function test_admin_can_view_event_create_page(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Super Admin');

        $response = $this->actingAs($admin)->get(route('admin.events.create'));

        $response->assertStatus(200);
    }
}
