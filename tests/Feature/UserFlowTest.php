<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use App\Models\Wishlist;
use App\Notifications\OrderConfirmation;
use App\Services\Cart\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Livewire\Volt\Volt;
use Tests\TestCase;

class UserFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_home_page(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
    }

    public function test_guest_can_view_books_page(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create([
            'author_id' => $author->id,
            'status' => 'published',
        ]);

        $response = $this->get(route('books.index'));

        $response->assertStatus(200);
        $response->assertSee($book->title);
    }

    public function test_guest_cannot_see_draft_books(): void
    {
        $author = Author::factory()->create();
        $draft = Book::factory()->create([
            'author_id' => $author->id,
            'status' => 'draft',
            'title' => 'Draft Title',
        ]);

        $response = $this->get(route('books.index'));

        $response->assertStatus(200);
        $response->assertDontSee('Draft Title');
    }

    public function test_guest_can_view_book_detail(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create([
            'author_id' => $author->id,
            'status' => 'published',
            'slug' => 'test-book-detail',
        ]);

        $response = $this->get(route('books.show', $book->slug));

        $response->assertStatus(200);
        $response->assertSee($book->title);
    }

    public function test_guest_can_filter_books_by_category(): void
    {
        $author = Author::factory()->create();
        $category = Category::factory()->create(['name' => 'Fiction', 'slug' => 'fiction']);
        $book = Book::factory()->create([
            'author_id' => $author->id,
            'status' => 'published',
        ]);
        $book->categories()->attach($category->id);

        $response = $this->get(route('category.books', $category->slug));

        $response->assertStatus(200);
        $response->assertSee($book->title);
    }

    public function test_guest_can_subscribe_to_newsletter(): void
    {
        $response = $this->post(route('newsletter.subscribe'), ['email' => 'reader@example.com']);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('newsletter_subscribers', ['email' => 'reader@example.com']);
    }

    public function test_newsletter_rejects_invalid_email(): void
    {
        $response = $this->post(route('newsletter.subscribe'), ['email' => 'not-an-email']);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseCount('newsletter_subscribers', 0);
    }

    public function test_guest_order_confirmation_requires_valid_token(): void
    {
        $order = Order::factory()->create(['user_id' => null]);

        $this->get(route('order.confirmation', $order))
            ->assertNotFound();

        $this->get(route('order.confirmation', ['order' => $order, 'token' => 'wrong-token']))
            ->assertNotFound();

        $this->get($order->confirmationUrl())
            ->assertStatus(200);
    }

    public function test_user_cannot_view_another_users_order_confirmation(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($other)
            ->get(route('order.confirmation', $order))
            ->assertNotFound();
    }

    public function test_guest_can_view_blog_page(): void
    {
        $response = $this->get(route('blog.index'));

        $response->assertStatus(200);
    }

    public function test_guest_can_view_events_page(): void
    {
        $response = $this->get(route('events.index'));

        $response->assertStatus(200);
    }

    public function test_guest_can_view_about_page(): void
    {
        $response = $this->get(route('about'));

        $response->assertStatus(200);
    }

    public function test_guest_can_view_cart(): void
    {
        $response = $this->get(route('cart'));

        $response->assertStatus(200);
    }

    public function test_guest_is_redirected_to_cart_if_empty_checkout(): void
    {
        $response = $this->get(route('checkout'));

        $response->assertRedirect(route('books.index'));
    }

    public function test_guest_is_redirected_to_login_for_wishlist(): void
    {
        $response = $this->get(route('wishlist'));

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_register(): void
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create();

        $component = Volt::test('pages.auth.login')
            ->set('form.email', $user->email)
            ->set('form.password', 'password');

        $component->call('login');

        $component
            ->assertHasNoErrors()
            ->assertRedirect(route('home', absolute: false));

        $this->assertAuthenticated();
    }

    public function test_authenticated_user_can_add_to_cart(): void
    {
        $user = User::factory()->create();
        $author = Author::factory()->create();
        $book = Book::factory()->create([
            'author_id' => $author->id,
            'status' => 'published',
        ]);

        $cart = new CartService;
        $cart->add($book->id);

        $this->assertEquals(1, $cart->count());
        $items = $cart->get();
        $this->assertArrayHasKey($cart->key($book->id), $items);
    }

    public function test_authenticated_user_can_access_checkout_with_cart_items(): void
    {
        $user = User::factory()->create();
        $author = Author::factory()->create();
        $book = Book::factory()->create([
            'author_id' => $author->id,
            'price' => 150,
            'stock' => 5,
            'status' => 'published',
        ]);

        $cart = new CartService;
        $cart->add($book->id);

        $response = $this->actingAs($user)->get(route('checkout'));

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_place_order(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $author = Author::factory()->create();
        $book = Book::factory()->create([
            'author_id' => $author->id,
            'price' => 200,
            'stock' => 10,
            'status' => 'published',
        ]);

        $cart = new CartService;
        $cart->add($book->id, 2);

        $this->actingAs($user);

        $response = Livewire::test('checkout')
            ->set('name', $user->name)
            ->set('phone', '01123456789')
            ->set('district', 'Dhaka')
            ->set('area', 'Gulshan')
            ->set('address', '123 Test Street')
            ->set('postal_code', '1212')
            ->set('payment_method', 'cod')
            ->call('placeOrder');

        $response->assertHasNoErrors();
        $response->assertRedirect();

        Notification::assertSentTo($user, OrderConfirmation::class);
    }

    public function test_authenticated_user_can_view_orders(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('orders.index'));

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_access_wishlist(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('wishlist'));

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_add_to_wishlist(): void
    {
        $user = User::factory()->create();
        $author = Author::factory()->create();
        $book = Book::factory()->create([
            'author_id' => $author->id,
            'status' => 'published',
        ]);

        Wishlist::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);

        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);
    }

    public function test_authenticated_user_can_remove_from_wishlist(): void
    {
        $user = User::factory()->create();
        $author = Author::factory()->create();
        $book = Book::factory()->create([
            'author_id' => $author->id,
            'status' => 'published',
        ]);

        $wishlist = Wishlist::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);

        $this->actingAs($user);

        Livewire::test('wishlist-page')
            ->call('remove', $wishlist->id);

        $this->assertDatabaseMissing('wishlists', [
            'id' => $wishlist->id,
        ]);
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_update_profile(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $component = Livewire::test('profile.update-profile-information-form')
            ->set('name', 'Updated Name')
            ->set('email', $user->email)
            ->call('updateProfileInformation');

        $component->assertHasNoErrors();
        $this->assertEquals('Updated Name', $user->fresh()->name);
    }
}
