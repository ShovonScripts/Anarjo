<?php

namespace App\Livewire\Admin;

use App\Models\BlogTag;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class BlogTags extends Component
{
    use WithPagination;

    public $tag_id;

    public $name;

    public $search = '';

    public $isOpen = false;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }

    public function render()
    {
        $query = BlogTag::withCount('blogs');

        if ($this->search) {
            $query->where('name', 'like', '%'.$this->search.'%');
        }

        $tags = $query->latest()->paginate(10);

        return view('livewire.admin.blog-tags', compact('tags'))
            ->layout('layouts.admin');
    }

    public function create()
    {
        $this->resetInput();
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $tag = BlogTag::findOrFail($id);
        $this->tag_id = $tag->id;
        $this->name = $tag->name;
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate();

        BlogTag::updateOrCreate(
            ['id' => $this->tag_id],
            [
                'name' => $this->name,
                'slug' => Str::slug($this->name),
            ]
        );

        session()->flash('message', $this->tag_id ? 'Tag updated.' : 'Tag created.');
        $this->resetInput();
        $this->isOpen = false;
    }

    public function delete($id)
    {
        $tag = BlogTag::withCount('blogs')->find($id);
        if ($tag && $tag->blogs_count > 0) {
            session()->flash('error', 'Cannot delete tag with existing posts.');

            return;
        }
        $tag?->delete();
        session()->flash('message', 'Tag deleted.');
    }

    private function resetInput()
    {
        $this->tag_id = null;
        $this->name = '';
    }
}
