<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ManagesHeroSlides;
use App\Livewire\Concerns\UploadsMedia;
use App\Models\HeroSlide;
use App\Services\SettingsService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mews\Purifier\Facades\Purifier;

class AboutSettings extends Component
{
    use ManagesHeroSlides, UploadsMedia, WithFileUploads;

    public function heroSlidePage(): string
    {
        return 'about';
    }

    public string $about_label = '';

    public string $about_hero_title = '';

    public string $about_subtitle = '';

    public string $about_content = '';

    public string $about_mission = '';

    public string $about_mission_heading = '';

    public string $about_vision = '';

    public string $about_vision_heading = '';

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

    public function render()
    {
        $slides = HeroSlide::where('page', $this->heroSlidePage())->orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.about-settings', compact('slides'))
            ->layout('layouts.admin');
    }
}
