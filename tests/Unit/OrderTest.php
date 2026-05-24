<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Book;
use App\Models\User;
use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_has_invoice(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'invoice_no' => 'INV-TEST-001',
            'subtotal' => 500,
            'shipping' => 60,
            'total' => 560,
            'payment_method' => 'cod',
        ]);

        $this->assertNotNull($order->invoice_no);
        $this->assertStringStartsWith('INV', $order->invoice_no);
    }

    public function test_order_calculates_total_correctly(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'invoice_no' => 'INV-TEST-002',
            'subtotal' => 1000,
            'shipping' => 0,
            'discount' => 50,
            'total' => 950,
        ]);

        $this->assertEquals(950, $order->total);
        $this->assertEquals(1000, $order->subtotal);
    }

    public function test_order_has_items(): void
    {
        $user = User::factory()->create();
        $author = \App\Models\Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->id, 'price' => 150]);

        $order = Order::create([
            'user_id' => $user->id,
            'invoice_no' => 'INV-TEST-003',
            'subtotal' => 300,
            'total' => 300,
        ]);

        $item = OrderItem::create([
            'order_id' => $order->id,
            'book_id' => $book->id,
            'price' => 150,
            'qty' => 2,
            'subtotal' => 300,
        ]);

        $this->assertCount(1, $order->fresh()->items);
        $this->assertEquals(300, $order->fresh()->items->first()->subtotal);
    }

    public function test_order_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'invoice_no' => 'INV-TEST-004',
            'subtotal' => 250,
            'total' => 250,
        ]);

        $this->assertInstanceOf(User::class, $order->user);
        $this->assertEquals($user->id, $order->user->id);
    }
}
