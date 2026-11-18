<?php

namespace App\Livewire;

use App\Models\Review;
use Livewire\Component;

class BookReviews extends Component
{
    public int $bookId;

    public int $rating = 5;

    public string $body = '';

    public ?Review $userReview = null;

    public function mount(): void
    {
        $this->loadReviews();
    }

    protected function loadReviews(): void
    {
        if (auth()->check()) {
            $this->userReview = Review::where('user_id', auth()->id())
                ->where('book_id', $this->bookId)
                ->first();
        }
    }

    public function submit(): void
    {
        if (! auth()->check()) {
            $this->dispatch('notify', message: 'Please login to review.');

            return;
        }

        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'body' => 'required|string|min:10|max:2000',
        ]);

        Review::updateOrCreate(
            ['user_id' => auth()->id(), 'book_id' => $this->bookId],
            ['rating' => $this->rating, 'review' => $this->body, 'status' => 'pending'],
        );

        $this->dispatch('notify', message: 'Review submitted for approval.');
        $this->loadReviews();
        $this->body = '';
    }

    public function render()
    {
        $reviews = Review::with('user')
            ->where('book_id', $this->bookId)
            ->where('status', 'approved')
            ->latest()
            ->get();

        return view('livewire.book-reviews', compact('reviews'));
    }
}
