<?php

namespace App\Livewire\Admin;

use App\Models\HeroSlide;
use App\Models\Media;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class HeroSlides extends Component
{
    use WithPagination, WithFileUploads;

    public $slide_id;
    public $link;
    public $sort_order = 0;
    public $status = true;
    public $image;
    public $new_image;
    public $isOpen = false;

    protected function rules(): array
    {
        return [
            'link' => 'nullable|string|max:255',
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
        $this->link = $slide->link;
        $this->sort_order = $slide->sort_order;
        $this->status = $slide->status;
        $this->image = $slide->image;
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate();

        $data = [
            'link' => $this->link,
            'sort_order' => $this->sort_order,
            'status' => $this->status,
        ];

        if ($this->new_image) {
            $path = $this->new_image->store('hero', 'public');
            $data['image'] = $path;
            Media::create([
                'name' => $this->new_image->getClientOriginalName(),
                'path' => $path,
                'type' => $this->new_image->getMimeType(),
                'size' => $this->new_image->getSize(),
                'disk' => 'public',
            ]);
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
            $slide->update(['status' => !$slide->status]);
        }
    }

    private function resetInputFields()
    {
        $this->slide_id = null;
        $this->link = '';
        $this->sort_order = 0;
        $this->status = true;
        $this->image = null;
        $this->new_image = null;
    }
}
