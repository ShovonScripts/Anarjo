<?php

namespace App\Livewire;

use App\Models\Wishlist;
use Livewire\Component;

class WishlistPage extends Component
{
    public function remove($id)
    {
        Wishlist::where('user_id', auth()->id())->where('id', $id)->delete();
        $this->dispatch('notify', message: 'Removed from wishlist.');
    }

    public function render()
    {
        $items = Wishlist::with('book.author')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('livewire.wishlist-page', compact('items'))
            ->layout('layouts.frontend');
    }
}
