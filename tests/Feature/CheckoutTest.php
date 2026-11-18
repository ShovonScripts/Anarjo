<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\User;
use App\Services\Cart\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_access_checkout_with_items(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->id, 'price' => 100]);

        $cart = new CartService;
        $cart->add($book->id);

        $response = $this->get(route('checkout'));
        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_access_checkout(): void
    {
        $user = User::factory()->create();
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->id, 'price' => 100]);

        $cart = new CartService;
        $cart->add($book->id);

        $response = $this->actingAs($user)->get(route('checkout'));
        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_place_order(): void
    {
        $user = User::factory()->create();
        $author = Author::factory()->create();
        $book = Book::factory()->create([
            'author_id' => $author->id,
            'price' => 100,
            'stock' => 10,
            'status' => 'published',
        ]);

        $cart = new CartService;
        $cart->add($book->id);

        $response = $this->actingAs($user)->get(route('checkout'));
        $response->assertStatus(200);
    }

    public function test_guest_can_place_order(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create([
            'author_id' => $author->id,
            'price' => 200,
            'stock' => 10,
            'status' => 'published',
        ]);

        $cart = new CartService;
        $cart->add($book->id, 2);

        $response = Livewire::test('checkout')
            ->set('email', 'guest@example.com')
            ->set('name', 'Guest User')
            ->set('phone', '01123456789')
            ->set('district', 'Dhaka')
            ->set('area', 'Gulshan')
            ->set('address', '123 Test Street')
            ->set('postal_code', '1212')
            ->set('payment_method', 'cod')
            ->call('placeOrder');

        $response->assertHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'guest_email' => 'guest@example.com',
            'user_id' => null,
        ]);
    }
}
