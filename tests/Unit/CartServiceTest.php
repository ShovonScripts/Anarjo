<?php

namespace Tests\Unit;

use App\Models\Author;
use App\Models\Book;
use App\Services\Cart\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    private CartService $cart;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cart = new CartService;
        session()->forget('cart');
    }

    public function test_cart_is_empty_by_default(): void
    {
        $this->assertTrue($this->cart->isEmpty());
        $this->assertEquals(0, $this->cart->count());
        $this->assertEquals(0, $this->cart->subtotal());
    }

    public function test_can_add_book_to_cart(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->id, 'price' => 100]);

        $this->cart->add($book->id, 2);

        $this->assertFalse($this->cart->isEmpty());
        $this->assertEquals(2, $this->cart->count());
        $this->assertEquals(200, $this->cart->subtotal());
    }

    public function test_can_update_quantity(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->id, 'price' => 50]);

        $this->cart->add($book->id, 1);
        $this->cart->update($this->cart->key($book->id), 3);

        $this->assertEquals(3, $this->cart->count());
        $this->assertEquals(150, $this->cart->subtotal());
    }

    public function test_removing_item_when_quantity_below_one(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->id, 'price' => 50]);

        $this->cart->add($book->id, 1);
        $this->cart->update($this->cart->key($book->id), 0);

        $this->assertTrue($this->cart->isEmpty());
    }

    public function test_can_remove_book(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->id, 'price' => 75]);

        $this->cart->add($book->id);
        $this->cart->remove($this->cart->key($book->id));

        $this->assertTrue($this->cart->isEmpty());
    }

    public function test_can_clear_cart(): void
    {
        $author = Author::factory()->create();
        $books = Book::factory()->count(3)->create(['author_id' => $author->id, 'price' => 30]);

        foreach ($books as $book) {
            $this->cart->add($book->id);
        }

        $this->cart->clear();

        $this->assertTrue($this->cart->isEmpty());
    }

    public function test_subtotal_calculates_correctly(): void
    {
        $author = Author::factory()->create();
        $book1 = Book::factory()->create(['author_id' => $author->id, 'price' => 100]);
        $book2 = Book::factory()->create(['author_id' => $author->id, 'price' => 200]);

        $this->cart->add($book1->id, 2);
        $this->cart->add($book2->id, 1);

        $this->assertEquals(400, $this->cart->subtotal());
    }
}
