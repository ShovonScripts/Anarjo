<?php

namespace App\Livewire;

use App\Services\Cart\CartService;
use Livewire\Component;

class AddToCartButton extends Component
{
    public int $bookId;
    public string $bookTitle = '';
    public string $size = 'default';

    public function add(CartService $cart): void
    {
        $cart->add($this->bookId);
        $this->dispatch('cart-updated');
        $this->dispatch('notify', message: "'{$this->bookTitle}' added to cart!");
    }

    public function render()
    {
        return view('livewire.add-to-cart-button');
    }
}
