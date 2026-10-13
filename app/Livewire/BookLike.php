<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\Like;
use Livewire\Component;

class BookLike extends Component
{
    public int $bookId;

    public bool $liked = false;

    public int $count = 0;

    public function mount(): void
    {
        $this->count = Like::where('likeable_type', Book::class)
            ->where('likeable_id', $this->bookId)
            ->count();

        if (auth()->check()) {
            $this->liked = Like::where('user_id', auth()->id())
                ->where('likeable_type', Book::class)
                ->where('likeable_id', $this->bookId)
                ->exists();
        }
    }

    public function toggle(): void
    {
        if (! auth()->check()) {
            $this->dispatch('notify', message: 'Please login to like.');

            return;
        }

        if ($this->liked) {
            Like::where('user_id', auth()->id())
                ->where('likeable_type', Book::class)
                ->where('likeable_id', $this->bookId)
                ->delete();
            $this->liked = false;
            $this->count--;
            $this->dispatch('notify', message: 'Like removed.');
        } else {
            Like::create([
                'user_id' => auth()->id(),
                'likeable_type' => Book::class,
                'likeable_id' => $this->bookId,
            ]);
            $this->liked = true;
            $this->count++;
            $this->dispatch('notify', message: 'Liked!');
        }
    }

    public function render()
    {
        return view('livewire.book-like');
    }
}
