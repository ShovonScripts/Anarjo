<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\Stationery;
use App\Services\Cart\CartService;
use Livewire\Component;

class AddToCartButton extends Component
{
    public int $bookId;

    public string $bookTitle = '';

    public string $size = 'default';

    public string $type = 'book';

    public int $qty = 1;

    public int $stock = 0;

    public bool $preOrder = false;

    public bool $justAdded = false;

    public function mount(): void
    {
        $model = $this->type === 'stationery' ? Stationery::class : Book::class;
        $product = $model::find($this->bookId);
        if ($product) {
            $this->stock = (int) $product->stock;
            $this->preOrder = (bool) ($product->pre_order ?? false);
        }
    }

    public function add(CartService $cart): void
    {
        if (! $this->preOrder && $this->stock <= 0) {
            $this->dispatch('notify', message: 'Sorry, this item is out of stock.');

            return;
        }

        $cart->add($this->bookId, $this->qty, $this->type);
        $this->dispatch('cart-updated');
        $this->justAdded = true;
        $this->dispatch('notify', message: "'{$this->bookTitle}' added to cart!", type: 'cart-added');
        $this->dispatch('scroll-to-cart');
    }

    public function getIsAvailable(): bool
    {
        return $this->preOrder || $this->stock > 0;
    }

    public function getStockLabel(): string
    {
        if ($this->preOrder) {
            return 'Pre-order';
        }
        if ($this->stock <= 0) {
            return 'Out of stock';
        }
        if ($this->stock <= 5) {
            return 'Only '.$this->stock.' left';
        }

        return '';
    }

    public function render()
    {
        return view('livewire.add-to-cart-button');
    }
}
