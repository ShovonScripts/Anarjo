<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\UploadsMedia;
use App\Models\HeroSlide;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class HeroSlides extends Component
{
    use UploadsMedia, WithFileUploads, WithPagination;

    public $slide_id;

    public $title;

    public $subtitle;

    public $description;

    public $link;

    public $link_text;

    public $sort_order = 0;

    public $status = true;

    public $image;

    public $new_image;

    public $isOpen = false;

    protected function rules(): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'link' => 'nullable|string|max:255',
            'link_text' => 'nullable|string|max:100',
            'sort_order' => 'required|integer|min:0',
            'status' => 'boolean',
            'new_image' => 'nullable|image|max:3072',
        ];
    }

    public function render()
    {
        $slides = HeroSlide::orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.hero-slides', compact('slides'))
            ->layout('layouts.admin');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $slide = HeroSlide::findOrFail($id);
        $this->slide_id = $slide->id;
        $this->title = $slide->title;
        $this->subtitle = $slide->subtitle;
        $this->description = $slide->description;
        $this->link = $slide->link;
        $this->link_text = $slide->link_text;
        $this->sort_order = $slide->sort_order;
        $this->status = $slide->status;
        $this->image = $slide->image;
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'link' => $this->link,
            'link_text' => $this->link_text,
            'sort_order' => $this->sort_order,
            'status' => $this->status,
        ];

        if ($this->new_image) {
            $data['image'] = $this->replaceMedia($this->new_image, 'hero', $this->image);
        }

        HeroSlide::updateOrCreate(['id' => $this->slide_id], $data);

        session()->flash('message', $this->slide_id ? 'Slide updated.' : 'Slide created.');
        $this->resetInputFields();
        $this->isOpen = false;
    }

    public function delete($id)
    {
        HeroSlide::find($id)?->delete();
        session()->flash('message', 'Slide deleted.');
    }

    public function toggleStatus($id)
    {
        $slide = HeroSlide::find($id);
        if ($slide) {
            $slide->update(['status' => ! $slide->status]);
        }
    }

    private function resetInputFields()
    {
        $this->slide_id = null;
        $this->title = '';
        $this->subtitle = '';
        $this->description = '';
        $this->link = '';
        $this->link_text = '';
        $this->sort_order = 0;
        $this->status = true;
        $this->image = null;
        $this->new_image = null;
    }
}
