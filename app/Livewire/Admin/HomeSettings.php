<?php

namespace App\Livewire\Admin;

use App\Models\HeroSlide;
use App\Models\Media;
use App\Services\SettingsService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mews\Purifier\Facades\Purifier;

class HomeSettings extends Component
{
    use WithFileUploads;

    public string $home_section_title = '';
    public string $home_about_bangla = '';
    public $home_about_image;
    public string $home_about_image_path = '';

    public $slide_id;
    public $slide_link;
    public $slide_sort_order = 0;
    public $slide_status = true;
    public $slide_image;
    public $slide_new_image;
    public $slideModal = false;

    protected SettingsService $settings;

    public function boot(SettingsService $settings): void
    {
        $this->settings = $settings;
    }

    public function mount(): void
    {
        $this->home_section_title = $this->settings->get('home_section_title', 'আনারজো পাবলিকেশনস');
        $this->home_about_bangla = $this->settings->get('home_about_bangla', '');
        $this->home_about_image_path = $this->settings->get('home_about_image', '');
    }

    public function save(): void
    {
        $data = [
            'home_section_title' => $this->home_section_title,
            'home_about_bangla' => Purifier::clean($this->home_about_bangla),
        ];

        if ($this->home_about_image && is_object($this->home_about_image)) {
            $path = $this->home_about_image->store('settings', 'public');
            $data['home_about_image'] = $path;
            $this->home_about_image_path = $path;
            Media::create([
                'name' => $this->home_about_image->getClientOriginalName(),
                'path' => $path,
                'type' => $this->home_about_image->getMimeType(),
                'size' => $this->home_about_image->getSize(),
                'disk' => 'public',
            ]);
            $this->home_about_image = null;
        }

        if (!empty($data)) {
            $this->settings->setMultiple($data);
        }

        $this->dispatch('settings-saved');
    }

    public function removeImage(): void
    {
        $this->settings->set('home_about_image', '');
        $this->home_about_image_path = '';
        $this->home_about_image = null;
    }

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
        ];

        if ($this->slide_new_image && is_object($this->slide_new_image)) {
            $path = $this->slide_new_image->store('hero', 'public');
            $data['image'] = $path;
            Media::create([
                'name' => $this->slide_new_image->getClientOriginalName(),
                'path' => $path,
                'type' => $this->slide_new_image->getMimeType(),
                'size' => $this->slide_new_image->getSize(),
                'disk' => 'public',
            ]);
        }

        $data['page'] = 'home';
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
            $slide->update(['status' => !$slide->status]);
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

    public function render()
    {
        $slides = HeroSlide::where('page', 'home')->orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(10);
        return view('livewire.admin.home-settings', compact('slides'))
            ->layout('layouts.admin');
    }
}
