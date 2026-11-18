<?php

namespace App\Livewire\Admin;

use App\Models\NewsletterSubscriber;
use Livewire\Component;
use Livewire\WithPagination;

class Newsletter extends Component
{
    use WithPagination;

    public string $search = '';

    public function toggleActive($id): void
    {
        $subscriber = NewsletterSubscriber::findOrFail($id);
        $subscriber->update(['is_active' => ! $subscriber->is_active]);
    }

    public function delete($id): void
    {
        NewsletterSubscriber::find($id)?->delete();
    }

    public function render()
    {
        $subscribers = NewsletterSubscriber::latest()
            ->when($this->search, fn ($q) => $q->where('email', 'like', "%{$this->search}%"))
            ->paginate(20);

        return view('livewire.admin.newsletter', compact('subscribers'))
            ->layout('layouts.admin');
    }
}
