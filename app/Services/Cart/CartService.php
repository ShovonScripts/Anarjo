<?php

namespace App\Services\Cart;

use App\Models\Book;
use App\Models\Stationery;

class CartService
{
    private const CART_KEY = 'cart';

    public function get(): array
    {
        return session(self::CART_KEY, []);
    }

    public function key(int $itemId, string $type = 'book'): string
    {
        return $type.'_'.$itemId;
    }

    public function add(int $itemId, int $qty = 1, string $type = 'book'): void
    {
        $cart = $this->get();
        $key = $this->key($itemId, $type);

        if (isset($cart[$key])) {
            $cart[$key]['qty'] += $qty;
        } else {
            $item = $type === 'stationery' ? Stationery::findOrFail($itemId) : Book::findOrFail($itemId);
            $cart[$key] = [
                'id' => $item->id,
                'type' => $type,
                'title' => $item->title,
                'slug' => $item->slug,
                'image' => $type === 'stationery' ? ($item->image ?? '') : ($item->cover_image ?? ''),
                'price' => $item->sale_price ?? $item->price,
                'qty' => $qty,
                'route' => $type === 'stationery' ? 'stationeries.show' : 'books.show',
            ];
        }

        session([self::CART_KEY => $cart]);
    }

    public function update(string $key, int $qty): void
    {
        $cart = $this->get();
        if ($qty < 1) {
            $this->remove($key);

            return;
        }
        if (isset($cart[$key])) {
            $cart[$key]['qty'] = $qty;
        }
        session([self::CART_KEY => $cart]);
    }

    public function remove(string $key): void
    {
        $cart = $this->get();
        unset($cart[$key]);
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
        return array_sum(array_map(fn ($item) => $item['price'] * $item['qty'], $this->get()));
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
