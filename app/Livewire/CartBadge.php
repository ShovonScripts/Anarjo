<?php

namespace App\Livewire;

use App\Services\Cart\CartService;
use Livewire\Component;

class CartBadge extends Component
{
    public int $count = 0;

    protected CartService $cartService;

    public function boot(CartService $cartService): void
    {
        $this->cartService = $cartService;
    }

    public function mount(): void
    {
        $this->count = $this->cartService->count();
    }

    protected function getListeners(): array
    {
        return ['cart-updated' => 'refreshCount'];
    }

    public function refreshCount(): void
    {
        $this->count = $this->cartService->count();
    }

    public function render()
    {
        return view('livewire.cart-badge');
    }
}
