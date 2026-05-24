<?php

namespace App\Livewire\Admin;

use App\Models\BlogCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;

class BlogCategories extends Component
{
    use WithPagination;

    public $category_id;
    public $name;
    public $description;
    public $search = '';
    public $isOpen = false;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
        ];
    }

    public function render()
    {
        $query = BlogCategory::withCount('blogs');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        $categories = $query->latest()->paginate(10);

        return view('livewire.admin.blog-categories', compact('categories'))
            ->layout('layouts.admin');
    }

    public function create()
    {
        $this->resetInput();
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $cat = BlogCategory::findOrFail($id);
        $this->category_id = $cat->id;
        $this->name = $cat->name;
        $this->description = $cat->description;
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate();

        BlogCategory::updateOrCreate(
            ['id' => $this->category_id],
            [
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'description' => $this->description,
            ]
        );

        session()->flash('message', $this->category_id ? 'Category updated.' : 'Category created.');
        $this->resetInput();
        $this->isOpen = false;
    }

    public function delete($id)
    {
        $cat = BlogCategory::withCount('blogs')->find($id);
        if ($cat && $cat->blogs_count > 0) {
            session()->flash('error', 'Cannot delete category with existing posts.');
            return;
        }
        $cat?->delete();
        session()->flash('message', 'Category deleted.');
    }

    private function resetInput()
    {
        $this->category_id = null;
        $this->name = '';
        $this->description = '';
    }
}
