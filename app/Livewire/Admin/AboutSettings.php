<?php

namespace App\Livewire\Admin;

use App\Models\HeroSlide;
use App\Models\Media;
use App\Services\SettingsService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mews\Purifier\Facades\Purifier;

class AboutSettings extends Component
{
    use WithFileUploads;

    public string $about_label = '';
    public string $about_hero_title = '';
    public string $about_subtitle = '';
    public string $about_content = '';
    public string $about_mission = '';
    public string $about_mission_heading = '';
    public string $about_vision = '';
    public string $about_vision_heading = '';

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
        $this->about_label = $this->settings->get('about_label', 'About Us');
        $this->about_hero_title = $this->settings->get('about_hero_title', 'We believe in the power of words');
        $this->about_subtitle = $this->settings->get('about_subtitle', '');
        $this->about_content = $this->settings->get('about_content', '');
        $this->about_mission = $this->settings->get('about_mission', '');
        $this->about_mission_heading = $this->settings->get('about_mission_heading', 'Our Mission');
        $this->about_vision = $this->settings->get('about_vision', '');
        $this->about_vision_heading = $this->settings->get('about_vision_heading', 'Our Vision');
    }

    public function save(): void
    {
        $data = [
            'about_label' => $this->about_label,
            'about_hero_title' => $this->about_hero_title,
            'about_subtitle' => $this->about_subtitle,
            'about_content' => Purifier::clean($this->about_content),
            'about_mission' => Purifier::clean($this->about_mission),
            'about_vision' => Purifier::clean($this->about_vision),
            'about_vision_heading' => $this->about_vision_heading,
        ];

        $this->settings->setMultiple($data);

        $this->dispatch('settings-saved');
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

        $data['page'] = 'about';
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
        $slides = HeroSlide::where('page', 'about')->orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(10);
        return view('livewire.admin.about-settings', compact('slides'))
            ->layout('layouts.admin');
    }
}
