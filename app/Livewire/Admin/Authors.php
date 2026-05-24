<?php

namespace App\Livewire\Admin;

use App\Models\Author;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class Authors extends Component
{
    use WithPagination;

    public $author_id;
    public $name;
    public $bio;
    public $facebook;
    public $linkedin;
    public $website;
    public $royalty_rate;
    public $status = 'active';
    public $search = '';
    public $isOpen = false;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'facebook' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'website' => 'nullable|url|max:255',
            'royalty_rate' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive',
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
        $this->facebook = $author->facebook;
        $this->linkedin = $author->linkedin;
        $this->website = $author->website;
        $this->royalty_rate = $author->royalty_rate;
        $this->status = $author->status;
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate();

        Author::updateOrCreate(
            ['id' => $this->author_id],
            [
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'bio' => $this->bio,
                'facebook' => $this->facebook,
                'linkedin' => $this->linkedin,
                'website' => $this->website,
                'royalty_rate' => $this->royalty_rate,
                'status' => $this->status,
            ]
        );

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
        $this->facebook = '';
        $this->linkedin = '';
        $this->website = '';
        $this->royalty_rate = 0;
        $this->status = 'active';
    }
}
