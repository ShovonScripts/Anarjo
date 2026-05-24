<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Author;
use App\Models\User;
use App\Services\Cart\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_checkout(): void
    {
        $response = $this->get(route('checkout'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_checkout(): void
    {
        $user = User::factory()->create();
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->id, 'price' => 100]);

        $cart = new CartService();
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

        $cart = new CartService();
        $cart->add($book->id);

        $response = $this->actingAs($user)->get(route('checkout'));
        $response->assertStatus(200);
    }
}
