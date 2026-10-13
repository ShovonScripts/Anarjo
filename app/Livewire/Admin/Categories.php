<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\UploadsMedia;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Categories extends Component
{
    use UploadsMedia, WithFileUploads, WithPagination;

    public $category_id;

    public $name;

    public $parent_id;

    public $image;

    public $existing_image;

    public $search = '';

    public $isOpen = false;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    public function render()
    {
        $categories = Category::with('parent')
            ->where('name', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate(10);

        $allCategories = Category::all();

        return view('livewire.admin.categories', compact('categories', 'allCategories'))
            ->layout('layouts.admin');
    }

    public function create()
    {
        $this->resetInput();
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $cat = Category::findOrFail($id);
        $this->category_id = $cat->id;
        $this->name = $cat->name;
        $this->parent_id = $cat->parent_id;
        $this->existing_image = $cat->image;
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'parent_id' => $this->parent_id,
        ];

        if ($this->image) {
            $data['image'] = $this->storeMedia($this->image, 'categories');
        } elseif ($this->category_id && ! $this->existing_image) {
            $data['image'] = null;
        }

        Category::updateOrCreate(['id' => $this->category_id], $data);

        session()->flash('message', $this->category_id ? 'Category updated.' : 'Category created.');
        $this->resetInput();
        $this->isOpen = false;
    }

    public function delete($id)
    {
        Category::find($id)?->delete();
        session()->flash('message', 'Category deleted.');
    }

    private function resetInput()
    {
        $this->category_id = null;
        $this->name = '';
        $this->parent_id = null;
        $this->image = null;
        $this->existing_image = null;
    }
}
