<?php

namespace Tests\Feature;

use App\Enums\PaymentStatus;
use App\Models\Author;
use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\Cart\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class PaymentSecurityTest extends TestCase
{
    use RefreshDatabase;

    private function makePendingOrder(float $total = 500): Order
    {
        $user = User::factory()->create();

        return Order::create([
            'user_id' => $user->id,
            'invoice_no' => 'INV-SEC-'.fake()->unique()->numberBetween(1000, 99999),
            'subtotal' => $total,
            'shipping' => 0,
            'total' => $total,
            'payment_method' => 'sslcommerz',
            'payment_status' => PaymentStatus::Pending->value,
        ]);
    }

    public function test_legacy_success_route_is_removed(): void
    {
        $user = User::factory()->create();
        $order = $this->makePendingOrder();

        $this->actingAs($user)
            ->post('/payment/success', ['invoice_no' => $order->invoice_no])
            ->assertNotFound();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => PaymentStatus::Pending->value,
        ]);
    }

    public function test_sslcommerz_callback_rejects_an_unverified_transaction(): void
    {
        Http::fake([
            '*' => Http::response(['status' => 'FAILED']),
        ]);

        $order = $this->makePendingOrder(500);

        $this->post(route('payment.sslcommerz.success'), [
            'tran_id' => $order->invoice_no,
            'amount' => '500',
            'currency' => 'BDT',
        ])->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => PaymentStatus::Failed->value,
        ]);
    }

    public function test_sslcommerz_callback_rejects_a_mismatched_amount(): void
    {
        Http::fake();

        $order = $this->makePendingOrder(500);

        $this->post(route('payment.sslcommerz.success'), [
            'tran_id' => $order->invoice_no,
            'amount' => '100',
            'currency' => 'BDT',
        ])->assertRedirect();

        Http::assertNothingSent();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => PaymentStatus::Failed->value,
        ]);
    }

    public function test_sslcommerz_ipn_marks_order_complete_only_when_validated(): void
    {
        Http::fake([
            '*' => Http::response(['status' => 'VALID']),
        ]);

        $order = $this->makePendingOrder(500);

        $this->post(route('payment.sslcommerz.ipn'), [
            'tran_id' => $order->invoice_no,
            'amount' => '500',
            'currency' => 'BDT',
        ])->assertOk();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => PaymentStatus::Completed->value,
        ]);
    }

    public function test_sslcommerz_success_does_not_downgrade_an_already_completed_order(): void
    {
        // Simulate IPN arriving before the customer redirect (docs guarantee this order).
        $order = $this->makePendingOrder(500);
        $order->update(['payment_status' => PaymentStatus::Completed->value]);

        $this->post(route('payment.sslcommerz.success'), [
            'tran_id' => $order->invoice_no,
            'amount' => '500',
            'currency' => 'BDT',
        ])->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => PaymentStatus::Completed->value,
        ]);
    }

    public function test_sslcommerz_cancel_does_not_downgrade_a_completed_order(): void
    {
        $order = $this->makePendingOrder(500);
        $order->update(['payment_status' => PaymentStatus::Completed->value]);

        $this->post(route('payment.sslcommerz.cancel'), [
            'tran_id' => $order->invoice_no,
            'amount' => '500',
            'currency' => 'BDT',
        ])->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => PaymentStatus::Completed->value,
        ]);
    }

    public function test_sslcommerz_cancel_marks_a_pending_order_cancelled(): void
    {
        $order = $this->makePendingOrder(500);

        $this->post(route('payment.sslcommerz.cancel'), [
            'tran_id' => $order->invoice_no,
            'amount' => '500',
            'currency' => 'BDT',
        ])->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => PaymentStatus::Cancelled->value,
        ]);
    }

    public function test_unpaid_order_restores_stock_when_payment_cancelled(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create([
            'author_id' => $author->id,
            'price' => 200,
            'stock' => 5,
            'status' => 'published',
        ]);

        $order = Order::create([
            'user_id' => $orderUser = User::factory()->create()->id,
            'invoice_no' => 'INV-RESTORE-'.fake()->unique()->numberBetween(1000, 99999),
            'subtotal' => 200,
            'shipping' => 0,
            'total' => 200,
            'payment_method' => 'sslcommerz',
            'payment_status' => PaymentStatus::Pending->value,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'book_id' => $book->id,
            'item_type' => 'book',
            'price' => 200,
            'qty' => 2,
            'subtotal' => 400,
        ]);

        $this->post(route('payment.sslcommerz.cancel'), [
            'tran_id' => $order->invoice_no,
            'amount' => '200',
            'currency' => 'BDT',
        ])->assertRedirect();

        $this->assertSame(7, $book->fresh()->stock);
        $this->assertTrue((bool) $order->fresh()->stock_restored);
    }

    public function test_failed_payment_does_not_restore_stock_twice(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create([
            'author_id' => $author->id,
            'price' => 200,
            'stock' => 5,
            'status' => 'published',
        ]);

        $order = Order::create([
            'user_id' => User::factory()->create()->id,
            'invoice_no' => 'INV-RESTORE2-'.fake()->unique()->numberBetween(1000, 99999),
            'subtotal' => 200,
            'shipping' => 0,
            'total' => 200,
            'payment_method' => 'sslcommerz',
            'payment_status' => PaymentStatus::Pending->value,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'book_id' => $book->id,
            'item_type' => 'book',
            'price' => 200,
            'qty' => 2,
            'subtotal' => 400,
        ]);

        $order->update(['payment_status' => PaymentStatus::Failed->value]);
        $order->update(['payment_status' => PaymentStatus::Cancelled->value]);

        $this->assertSame(7, $book->fresh()->stock);
    }

    public function test_checkout_uses_db_price_and_caps_oversell(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create([
            'author_id' => $author->id,
            'price' => 200,
            'stock' => 0,
            'status' => 'published',
        ]);

        $cart = new CartService;

        // Simulate a tampered/stale cart snapshot with a cheap price and high qty.
        $cartKey = $cart->key($book->id, 'book');
        session()->put('cart', [
            $cartKey => [
                'id' => $book->id,
                'type' => 'book',
                'title' => $book->title,
                'slug' => $book->slug,
                'image' => $book->cover_image ?? '',
                'price' => 10,
                'qty' => 99,
                'route' => 'books.show',
            ],
        ]);

        Livewire::test('checkout')
            ->set('email', 'guest@example.com')
            ->set('name', 'Guest User')
            ->set('phone', '01123456789')
            ->set('district', 'Dhaka')
            ->set('area', 'Gulshan')
            ->set('address', '123 Test Street')
            ->set('payment_method', 'cod')
            ->call('placeOrder')
            ->assertHasErrors('cart');

        $this->assertDatabaseCount('orders', 0);
    }

    public function test_checkout_uses_fresh_db_price_for_order_lines(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create([
            'author_id' => $author->id,
            'price' => 200,
            'sale_price' => 150,
            'stock' => 5,
            'status' => 'published',
        ]);

        $cart = new CartService;
        $cartKey = $cart->key($book->id, 'book');

        session()->put('cart', [
            $cartKey => [
                'id' => $book->id,
                'type' => 'book',
                'title' => $book->title,
                'slug' => $book->slug,
                'image' => '',
                'price' => 5,
                'qty' => 2,
                'route' => 'books.show',
            ],
        ]);

        Livewire::test('checkout')
            ->set('email', 'guest@example.com')
            ->set('name', 'Guest User')
            ->set('phone', '01123456789')
            ->set('district', 'Dhaka')
            ->set('area', 'Gulshan')
            ->set('address', '123 Test Street')
            ->set('payment_method', 'cod')
            ->call('placeOrder')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('order_items', [
            'book_id' => $book->id,
            'price' => 150,
            'qty' => 2,
            'subtotal' => 300,
        ]);

        // Stock reserved during checkout.
        $this->assertSame(3, $book->fresh()->stock);
    }

    public function test_guest_can_access_payment_page_with_token(): void
    {
        $order = Order::create([
            'user_id' => null,
            'guest_email' => 'guest@example.com',
            'invoice_no' => 'INV-GUEST-'.fake()->unique()->numberBetween(1000, 99999),
            'subtotal' => 500,
            'shipping' => 50,
            'total' => 550,
            'payment_method' => 'bkash',
            'payment_status' => PaymentStatus::Pending->value,
            'confirmation_token' => 'test-token-12345',
        ]);

        $this->get(route('payment.process', ['order' => $order, 'token' => 'test-token-12345']))
            ->assertOk();
    }

    public function test_guest_cannot_access_payment_page_without_token(): void
    {
        $order = Order::create([
            'user_id' => null,
            'guest_email' => 'guest@example.com',
            'invoice_no' => 'INV-GUEST2-'.fake()->unique()->numberBetween(1000, 99999),
            'subtotal' => 500,
            'shipping' => 50,
            'total' => 550,
            'payment_method' => 'bkash',
            'payment_status' => PaymentStatus::Pending->value,
            'confirmation_token' => 'test-token-67890',
        ]);

        $this->get(route('payment.process', ['order' => $order]))
            ->assertForbidden();
    }

    public function test_sslcommerz_validation_with_val_id_completes_order(): void
    {
        $order = $this->makePendingOrder(500);

        Http::fake([
            '*validator/api/validationserverAPI.php*' => Http::response([
                'status' => 'VALID',
                'tran_id' => $order->invoice_no,
                'amount' => '500.00',
                'currency' => 'BDT',
            ]),
        ]);

        $this->post(route('payment.sslcommerz.success'), [
            'tran_id' => $order->invoice_no,
            'val_id' => 'val_12345678',
            'amount' => '500',
            'currency' => 'BDT',
        ])->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'payment_status' => PaymentStatus::Completed->value,
            'payment_id' => 'val_12345678',
        ]);
    }
}
