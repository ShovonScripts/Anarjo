<?php

namespace App\Livewire;

use App\Models\Book;
use App\Services\Cart\CartService;
use Livewire\Component;

class Cart extends Component
{
    protected CartService $cartService;

    public function boot(CartService $cartService): void
    {
        $this->cartService = $cartService;
    }

    public function addToCart(int $bookId, int $qty = 1): void
    {
        $this->cartService->add($bookId, $qty);
        $this->dispatch('cart-updated');
        $this->dispatch('notify', message: 'Book added to cart!');
    }

    public function updateQty(int $bookId, int $qty): void
    {
        $this->cartService->update($bookId, $qty);
        $this->dispatch('cart-updated');
    }

    public function remove(int $bookId): void
    {
        $this->cartService->remove($bookId);
        $this->dispatch('cart-updated');
    }

    public function clear(): void
    {
        $this->cartService->clear();
        $this->dispatch('cart-updated');
    }

    public function render()
    {
        $items = $this->cartService->get();
        $subtotal = $this->cartService->subtotal();
        $count = $this->cartService->count();
        $flatRate = (int) (setting('shipping_flat_rate_outside', '100'));
        $freeThreshold = (int) (setting('shipping_free_threshold', '500'));
        $shipping = $subtotal >= $freeThreshold ? 0 : $flatRate;
        $total = $subtotal + $shipping;
        $shippingProgress = min(100, $freeThreshold > 0 ? ($subtotal / $freeThreshold) * 100 : 100);

        $relatedBooks = Book::with('author')
            ->where('status', 'published')
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('livewire.cart', compact(
            'items', 'subtotal', 'count', 'shipping', 'total',
            'flatRate', 'freeThreshold', 'shippingProgress', 'relatedBooks'
        ))->layout('layouts.frontend');
    }
}
