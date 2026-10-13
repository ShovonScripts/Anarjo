<?php

namespace App\Livewire\Admin;

use App\Models\Comment;
use Livewire\Component;
use Livewire\WithPagination;

class Comments extends Component
{
    use WithPagination;

    public string $filter = '';

    public string $search = '';

    public function render()
    {
        $comments = Comment::with('user', 'commentable')
            ->when($this->filter, fn ($q) => $q->where('status', $this->filter))
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->whereHas('commentable', fn ($c) => $c->where('title', 'like', "%{$this->search}%"))
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$this->search}%"));
            }))
            ->latest()
            ->paginate(15);

        return view('livewire.admin.comments', compact('comments'))
            ->layout('layouts.admin');
    }

    public function approve($id): void
    {
        Comment::where('id', $id)->update(['status' => 'approved']);
    }

    public function reject($id): void
    {
        Comment::where('id', $id)->update(['status' => 'rejected']);
    }

    public function delete($id): void
    {
        Comment::find($id)?->delete();
    }
}
