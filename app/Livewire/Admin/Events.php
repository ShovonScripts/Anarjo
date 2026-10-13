<?php

namespace App\Livewire\Admin;

use App\Models\Event;
use Livewire\Component;
use Livewire\WithPagination;

class Events extends Component
{
    use WithPagination;

    public $search = '';

    public $statusFilter = '';

    public function render()
    {
        $query = Event::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('venue', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $events = $query->latest()->paginate(10);

        return view('livewire.admin.events', compact('events'))
            ->layout('layouts.admin');
    }

    public function toggleMajor($id)
    {
        $event = Event::find($id);
        if ($event) {
            $event->update(['is_major' => ! $event->is_major]);
            session()->flash('message', $event->is_major ? 'Event marked as major.' : 'Event unmarked as major.');
        }
    }

    public function delete($id)
    {
        Event::find($id)?->delete();
        session()->flash('message', 'Event deleted.');
    }
}
