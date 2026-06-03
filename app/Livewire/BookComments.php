<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\Comment;
use Livewire\Component;

class BookComments extends Component
{
    public int $bookId;
    public string $body = '';

    protected $rules = [
        'body' => 'required|min:2|max:2000',
    ];

    public function addComment(): void
    {
        $this->validate();

        Comment::create([
            'user_id' => auth()->id(),
            'commentable_type' => Book::class,
            'commentable_id' => $this->bookId,
            'body' => $this->body,
            'status' => 'approved',
        ]);

        $this->body = '';
        $this->dispatch('comment-added');
    }

    public function render()
    {
        $comments = Comment::with('user')
            ->where('commentable_type', Book::class)
            ->where('commentable_id', $this->bookId)
            ->where('status', 'approved')
            ->latest()
            ->get();

        return view('livewire.book-comments', compact('comments'));
    }
}
