<?php

namespace App\Livewire\Admin;

use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;

class Reviews extends Component
{
    use WithPagination;

    public string $filter = '';

    public string $search = '';

    public function render()
    {
        $reviews = Review::with('user', 'book')
            ->when($this->filter, fn ($q) => $q->where('status', $this->filter))
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->whereHas('book', fn ($b) => $b->where('title', 'like', "%{$this->search}%"))
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$this->search}%"));
            }))
            ->latest()
            ->paginate(15);

        return view('livewire.admin.reviews', compact('reviews'))
            ->layout('layouts.admin');
    }

    public function approve($id): void
    {
        Review::where('id', $id)->update(['status' => 'approved']);
    }

    public function reject($id): void
    {
        Review::where('id', $id)->update(['status' => 'rejected']);
    }

    public function delete($id): void
    {
        Review::find($id)?->delete();
    }
}
