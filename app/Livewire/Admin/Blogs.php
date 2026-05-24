<?php

namespace App\Livewire\Admin;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Livewire\Component;
use Livewire\WithPagination;

class Blogs extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $categoryFilter = '';

    public function render()
    {
        $query = Blog::with(['category', 'tags']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('content', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->categoryFilter) {
            $query->where('blog_category_id', $this->categoryFilter);
        }

        $blogs = $query->latest()->paginate(10);
        $categories = BlogCategory::orderBy('name')->get();
        $allTags = BlogTag::orderBy('name')->get();

        return view('livewire.admin.blogs', compact('blogs', 'categories', 'allTags'))
            ->layout('layouts.admin');
    }

    public function delete($id)
    {
        Blog::find($id)?->delete();
        session()->flash('message', 'Blog deleted.');
    }
}
