<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\UploadsMedia;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mews\Purifier\Facades\Purifier;

class BlogForm extends Component
{
    use UploadsMedia, WithFileUploads;

    public $blog_id;

    public $title;

    public $content;

    public $featured_image;

    public $existing_featured_image;

    public $blog_category_id = '';

    public $tags = [];

    public $author;

    public $status = 'draft';

    public $meta_title;

    public $meta_description;

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:blog_tags,id',
            'author' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ];
    }

    public function mount($blog = null): void
    {
        if ($blog) {
            $blog = $blog instanceof Blog ? $blog : Blog::with('tags')->findOrFail($blog);
            $this->blog_id = $blog->id;
            $this->title = $blog->title;
            $this->content = $blog->content;
            $this->existing_featured_image = $blog->featured_image;
            $this->blog_category_id = (string) ($blog->blog_category_id ?? '');
            $this->tags = $blog->tags->pluck('id')->map(fn ($id) => (string) $id)->toArray();
            $this->author = $blog->author;
            $this->status = $blog->status;
            $this->meta_title = $blog->meta_title;
            $this->meta_description = $blog->meta_description;
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'content' => Purifier::clean($this->content),
            'blog_category_id' => $this->blog_category_id ?: null,
            'author' => $this->author ?? auth()->user()->name,
            'status' => $this->status,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'published_at' => $this->status === 'published' && ! $this->blog_id
                ? now()
                : ($this->status === 'published' ? Blog::find($this->blog_id)?->published_at ?? now() : null),
        ];

        if ($this->featured_image) {
            $data['featured_image'] = $this->storeMedia($this->featured_image, 'blog-images');
        } elseif ($this->blog_id && ! $this->existing_featured_image) {
            $data['featured_image'] = null;
        }

        $blog = Blog::updateOrCreate(['id' => $this->blog_id], $data);

        if ($this->tags) {
            $blog->tags()->sync($this->tags);
        } else {
            $blog->tags()->detach();
        }

        session()->flash('message', $this->blog_id ? 'Blog updated.' : 'Blog created.');

        $this->redirect(route('admin.blogs'), navigate: true);
    }

    public function removeFeaturedImage()
    {
        $this->featured_image = null;
        $this->existing_featured_image = null;
    }

    public function render()
    {
        $categories = BlogCategory::orderBy('name')->get();
        $allTags = BlogTag::orderBy('name')->get();

        return view('livewire.admin.blog-form', compact('categories', 'allTags'))
            ->layout('layouts.admin');
    }
}
