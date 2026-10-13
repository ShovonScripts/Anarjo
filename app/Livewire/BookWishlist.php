<?php

namespace App\Livewire;

use App\Models\Wishlist;
use Livewire\Component;

class BookWishlist extends Component
{
    public int $bookId;

    public bool $wishlisted = false;

    public function mount(): void
    {
        if (auth()->check()) {
            $this->wishlisted = Wishlist::where('user_id', auth()->id())
                ->where('book_id', $this->bookId)
                ->exists();
        }
    }

    public function toggle(): void
    {
        if (! auth()->check()) {
            $this->dispatch('notify', message: 'Please login to add to wishlist.');

            return;
        }

        if ($this->wishlisted) {
            Wishlist::where('user_id', auth()->id())
                ->where('book_id', $this->bookId)
                ->delete();
            $this->wishlisted = false;
            $this->dispatch('notify', message: 'Removed from wishlist.');
        } else {
            Wishlist::create([
                'user_id' => auth()->id(),
                'book_id' => $this->bookId,
            ]);
            $this->wishlisted = true;
            $this->dispatch('notify', message: 'Added to wishlist!');
        }
    }

    public function render()
    {
        return view('livewire.book-wishlist');
    }
}
