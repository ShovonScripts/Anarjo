<?php

namespace App\Livewire;

use App\Models\Blog;
use App\Models\Comment;
use Livewire\Component;

class BlogComments extends Component
{
    public int $blogId;
    public string $body = '';

    protected $rules = [
        'body' => 'required|min:2|max:2000',
    ];

    public function addComment(): void
    {
        $this->validate();

        Comment::create([
            'user_id' => auth()->id(),
            'commentable_type' => Blog::class,
            'commentable_id' => $this->blogId,
            'body' => $this->body,
            'status' => 'approved',
        ]);

        $this->body = '';
        $this->dispatch('comment-added');
    }

    public function render()
    {
        $comments = Comment::with('user')
            ->where('commentable_type', Blog::class)
            ->where('commentable_id', $this->blogId)
            ->where('status', 'approved')
            ->latest()
            ->get();

        return view('livewire.blog-comments', compact('comments'));
    }
}
