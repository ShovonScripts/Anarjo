<?php

namespace App\Livewire;

use App\Models\Book;
use App\Services\Cart\CartService;
use Livewire\Component;

class Cart extends Component
{
    public bool $confirmClear = false;

    public string $removingKey = '';

    protected CartService $cartService;

    public function boot(CartService $cartService): void
    {
        $this->cartService = $cartService;
    }

    public function addToCart(int $itemId, int $qty = 1, string $type = 'book'): void
    {
        $this->cartService->add($itemId, $qty, $type);
        $this->dispatch('cart-updated');
        $this->dispatch('notify', message: 'Item added to cart!');
    }

    public function updateQty(string $key, int $qty): void
    {
        $qty = max(1, min(99, $qty));
        $this->cartService->update($key, $qty);
        $this->dispatch('cart-updated');
    }

    public function decrement(string $key): void
    {
        $item = $this->cartService->get()[$key] ?? null;
        if (! $item) {
            return;
        }
        if ($item['qty'] <= 1) {
            $this->cartService->remove($key);
        } else {
            $this->cartService->update($key, $item['qty'] - 1);
        }
        $this->dispatch('cart-updated');
    }

    public function remove(string $key): void
    {
        $this->removingKey = $key;
        $item = $this->cartService->get()[$key] ?? null;
        $this->cartService->remove($key);
        $this->dispatch('cart-updated');
        $this->dispatch('notify', message: $item ? "'{$item['title']}' removed from cart." : 'Item removed.');
        $this->removingKey = '';
    }

    public function confirmClear(): void
    {
        $this->confirmClear = true;
    }

    public function clear(): void
    {
        $this->cartService->clear();
        $this->confirmClear = false;
        $this->dispatch('cart-updated');
        $this->dispatch('notify', message: 'Cart cleared.');
    }

    public function cancelClear(): void
    {
        $this->confirmClear = false;
    }

    public function render()
    {
        $items = $this->cartService->get();
        $subtotal = $this->cartService->subtotal();
        $count = $this->cartService->count();
        $insideDhakaRate = (int) (setting('shipping_flat_rate_inside', '60'));
        $outsideDhakaRate = (int) (setting('shipping_flat_rate_outside', '100'));
        $freeThreshold = (int) (setting('shipping_free_threshold', '500'));
        $shipping = $subtotal >= $freeThreshold ? 0 : $outsideDhakaRate;
        $total = $subtotal + $shipping;
        $shippingProgress = $freeThreshold > 0 ? min(100, ($subtotal / $freeThreshold) * 100) : 100;
        $amountToFree = max(0, $freeThreshold - $subtotal);

        $relatedBooks = Book::with('author')
            ->where('status', 'published')
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('livewire.cart', compact(
            'items', 'subtotal', 'count', 'shipping', 'total',
            'freeThreshold', 'shippingProgress', 'amountToFree', 'relatedBooks'
        ))->layout('layouts.frontend');
    }
}
