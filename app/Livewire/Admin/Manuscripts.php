<?php

namespace App\Livewire\Admin;

use App\Models\Manuscript;
use Livewire\Component;
use Livewire\WithPagination;

class Manuscripts extends Component
{
    use WithPagination;

    public $manuscript_id;
    public $status = 'pending';
    public $remarks;
    public $search = '';
    public $isOpen = false;

    public function render()
    {
        $manuscripts = Manuscript::where(function ($q) {
            $q->where('author_name', 'like', '%' . $this->search . '%')
              ->orWhere('book_title', 'like', '%' . $this->search . '%');
        })
        ->latest()
        ->paginate(10);

        return view('livewire.admin.manuscripts', compact('manuscripts'))
            ->layout('layouts.admin');
    }

    public function review($id)
    {
        $m = Manuscript::findOrFail($id);
        $this->manuscript_id = $m->id;
        $this->status = $m->status;
        $this->remarks = $m->remarks;
        $this->isOpen = true;
    }

    public function updateReview()
    {
        $this->validate([
            'status' => 'required|in:pending,approved,rejected',
            'remarks' => 'nullable|string',
        ]);

        Manuscript::find($this->manuscript_id)->update([
            'status' => $this->status,
            'remarks' => $this->remarks,
        ]);

        session()->flash('message', 'Manuscript review updated.');
        $this->isOpen = false;
    }

    public function delete($id)
    {
        Manuscript::find($id)?->delete();
        session()->flash('message', 'Manuscript deleted.');
    }
}
