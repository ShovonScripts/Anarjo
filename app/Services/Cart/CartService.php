<?php

namespace App\Services\Cart;

use App\Models\Book;

class CartService
{
    private const CART_KEY = 'cart';

    public function get(): array
    {
        return session(self::CART_KEY, []);
    }

    public function add(int $bookId, int $qty = 1): void
    {
        $cart = $this->get();
        $book = Book::findOrFail($bookId);

        if (isset($cart[$bookId])) {
            $cart[$bookId]['qty'] += $qty;
        } else {
            $cart[$bookId] = [
                'id' => $book->id,
                'title' => $book->title,
                'slug' => $book->slug,
                'cover_image' => $book->cover_image,
                'price' => $book->sale_price ?? $book->price,
                'qty' => $qty,
            ];
        }

        session([self::CART_KEY => $cart]);
    }

    public function update(int $bookId, int $qty): void
    {
        $cart = $this->get();
        if ($qty < 1) {
            $this->remove($bookId);
            return;
        }
        if (isset($cart[$bookId])) {
            $cart[$bookId]['qty'] = $qty;
        }
        session([self::CART_KEY => $cart]);
    }

    public function remove(int $bookId): void
    {
        $cart = $this->get();
        unset($cart[$bookId]);
        session([self::CART_KEY => $cart]);
    }

    public function clear(): void
    {
        session()->forget(self::CART_KEY);
    }

    public function count(): int
    {
        return array_sum(array_column($this->get(), 'qty'));
    }

    public function subtotal(): float
    {
        return array_sum(array_map(fn($item) => $item['price'] * $item['qty'], $this->get()));
    }

    public function total(): float
    {
        return $this->subtotal();
    }

    public function isEmpty(): bool
    {
        return empty($this->get());
    }
}
