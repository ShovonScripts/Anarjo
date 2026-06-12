<?php

namespace App\Livewire\Admin;

use App\Models\Author;
use App\Models\Media;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class Authors extends Component
{
    use WithPagination, WithFileUploads;

    public $author_id;
    public $name;
    public $bio;
    public $status = 'active';
    public $photo;
    public $existing_photo;
    public $search = '';
    public $isOpen = false;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    public function render()
    {
        $authors = Author::where('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.authors', compact('authors'))
            ->layout('layouts.admin');
    }

    public function create()
    {
        $this->resetInput();
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $author = Author::findOrFail($id);
        $this->author_id = $author->id;
        $this->name = $author->name;
        $this->bio = $author->bio;
        $this->status = $author->status;
        $this->existing_photo = $author->photo;
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'bio' => $this->bio,
            'status' => $this->status,
        ];

        if ($this->photo) {
            $path = $this->photo->store('authors', 'public');
            $data['photo'] = $path;
            Media::create([
                'name' => $this->photo->getClientOriginalName(),
                'path' => $path,
                'type' => $this->photo->getMimeType(),
                'size' => $this->photo->getSize(),
                'disk' => 'public',
            ]);
        } elseif ($this->author_id && !$this->existing_photo) {
            $data['photo'] = null;
        }

        Author::updateOrCreate(['id' => $this->author_id], $data);

        session()->flash('message', $this->author_id ? 'Author updated.' : 'Author created.');
        $this->resetInput();
        $this->isOpen = false;
    }

    public function delete($id)
    {
        Author::find($id)?->delete();
        session()->flash('message', 'Author deleted.');
    }

    private function resetInput()
    {
        $this->author_id = null;
        $this->name = '';
        $this->bio = '';
        $this->status = 'active';
        $this->photo = null;
        $this->existing_photo = null;
    }
}
