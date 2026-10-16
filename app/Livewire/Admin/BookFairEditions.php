<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\UploadsMedia;
use App\Models\BookFairEdition;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class BookFairEditions extends Component
{
    use UploadsMedia, WithFileUploads, WithPagination;

    public $edition_id;

    public $title;

    public $tagline;

    public $start_date;

    public $end_date;

    public $venue;

    public $image;

    public $existing_image;

    public $is_current = false;

    public $status = 'draft';

    public $search = '';

    public $isOpen = false;

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:500',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'venue' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_current' => 'boolean',
            'status' => 'required|in:draft,published',
        ];
    }

    public function render()
    {
        $editions = BookFairEdition::where(function ($q) {
            $q->where('title', 'like', '%'.$this->search.'%')
                ->orWhere('venue', 'like', '%'.$this->search.'%');
        })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.book-fair-editions', compact('editions'))
            ->layout('layouts.admin');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $edition = BookFairEdition::findOrFail($id);
        $this->edition_id = $edition->id;
        $this->title = $edition->title;
        $this->tagline = $edition->tagline;
        $this->start_date = $edition->start_date?->format('Y-m-d');
        $this->end_date = $edition->end_date?->format('Y-m-d');
        $this->venue = $edition->venue;
        $this->existing_image = $edition->image;
        $this->is_current = $edition->is_current;
        $this->status = $edition->status;
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'tagline' => $this->tagline,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'venue' => $this->venue,
            'is_current' => $this->is_current,
            'status' => $this->status,
        ];

        if ($this->image) {
            $data['image'] = $this->replaceMedia($this->image, 'book-fair-editions', $this->existing_image);
        } elseif ($this->edition_id && ! $this->existing_image) {
            $data['image'] = null;
        }

        $slug = Str::slug($this->title);
        $existing = BookFairEdition::where('slug', $slug)->where('id', '!=', $this->edition_id)->exists();
        if ($existing) {
            $slug .= '-'.Str::random(4);
        }
        $data['slug'] = $slug;

        BookFairEdition::updateOrCreate(['id' => $this->edition_id], $data);

        session()->flash('message', $this->edition_id ? 'Book Fair Edition updated.' : 'Book Fair Edition created.');
        $this->resetInputFields();
        $this->isOpen = false;
    }

    public function delete($id)
    {
        BookFairEdition::find($id)?->delete();
        session()->flash('message', 'Book Fair Edition deleted.');
    }

    private function resetInputFields()
    {
        $this->edition_id = null;
        $this->title = '';
        $this->tagline = '';
        $this->start_date = null;
        $this->end_date = null;
        $this->venue = '';
        $this->image = null;
        $this->existing_image = null;
        $this->is_current = false;
        $this->status = 'draft';
    }
}
