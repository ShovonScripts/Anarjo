<?php

namespace App\Livewire\Concerns;

use App\Models\HeroSlide;

trait ManagesHeroSlides
{
    public $slide_id;

    public $slide_link;

    public $slide_sort_order = 0;

    public $slide_status = true;

    public $slide_image;

    public $slide_new_image;

    public $slideModal = false;

    abstract public function heroSlidePage(): string;

    public function createSlide(): void
    {
        $this->resetSlideFields();
        $this->slideModal = true;
    }

    public function editSlide($id): void
    {
        $slide = HeroSlide::findOrFail($id);
        $this->slide_id = $slide->id;
        $this->slide_link = $slide->link;
        $this->slide_sort_order = $slide->sort_order;
        $this->slide_status = $slide->status;
        $this->slide_image = $slide->image;
        $this->slideModal = true;
    }

    public function storeSlide(): void
    {
        $this->validate([
            'slide_link' => 'nullable|string|max:255',
            'slide_sort_order' => 'required|integer|min:0',
            'slide_status' => 'boolean',
            'slide_new_image' => 'nullable|image|max:3072',
        ]);

        $data = [
            'link' => $this->slide_link,
            'sort_order' => $this->slide_sort_order,
            'status' => $this->slide_status,
            'page' => $this->heroSlidePage(),
        ];

        if ($this->slide_new_image && is_object($this->slide_new_image)) {
            $data['image'] = $this->storeMedia($this->slide_new_image, 'hero');
        }

        HeroSlide::updateOrCreate(['id' => $this->slide_id], $data);

        $this->dispatch('slide-saved');
        $this->resetSlideFields();
        $this->slideModal = false;
    }

    public function deleteSlide($id): void
    {
        HeroSlide::find($id)?->delete();
    }

    public function toggleSlide($id): void
    {
        $slide = HeroSlide::find($id);
        if ($slide) {
            $slide->update(['status' => ! $slide->status]);
        }
    }

    private function resetSlideFields(): void
    {
        $this->slide_id = null;
        $this->slide_link = '';
        $this->slide_sort_order = 0;
        $this->slide_status = true;
        $this->slide_image = null;
        $this->slide_new_image = null;
    }
}
