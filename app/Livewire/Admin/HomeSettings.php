<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\ManagesHeroSlides;
use App\Livewire\Concerns\UploadsMedia;
use App\Models\HeroSlide;
use App\Services\SettingsService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mews\Purifier\Facades\Purifier;

class HomeSettings extends Component
{
    use ManagesHeroSlides, UploadsMedia, WithFileUploads;

    public function heroSlidePage(): string
    {
        return 'home';
    }

    public string $home_section_title = '';

    public string $home_about_bangla = '';

    public $home_about_image;

    public string $home_about_image_path = '';

    public array $boxes = [];

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

        for ($i = 1; $i <= 4; $i++) {
            $this->boxes[$i] = [
                'title' => $this->settings->get("branding_box_{$i}_title", ''),
                'link' => $this->settings->get("branding_box_{$i}_link", ''),
                'image' => null,
                'image_path' => $this->settings->get("branding_box_{$i}_image", ''),
            ];
        }
    }

    public function save(): void
    {
        $data = [
            'home_section_title' => $this->home_section_title,
            'home_about_bangla' => Purifier::clean($this->home_about_bangla),
        ];

        for ($i = 1; $i <= 4; $i++) {
            $box = $this->boxes[$i];
            $data["branding_box_{$i}_title"] = $box['title'];
            $data["branding_box_{$i}_link"] = $box['link'];
            $data["branding_box_{$i}_image"] = $box['image_path'];

            if (! empty($box['image']) && is_object($box['image'])) {
                $path = $this->storeMedia($box['image'], 'branding');
                $data["branding_box_{$i}_image"] = $path;
                $this->boxes[$i]['image_path'] = $path;
                $this->boxes[$i]['image'] = null;
            }
        }

        if ($this->home_about_image && is_object($this->home_about_image)) {
            $path = $this->storeMedia($this->home_about_image, 'settings');
            $data['home_about_image'] = $path;
            $this->home_about_image_path = $path;
            $this->home_about_image = null;
        }

        if (! empty($data)) {
            $this->settings->setMultiple($data);
        }

        $this->dispatch('settings-saved');
    }

    public function removeBoxImage(int $index): void
    {
        $this->boxes[$index]['image_path'] = '';
        $this->boxes[$index]['image'] = null;
        $this->settings->set("branding_box_{$index}_image", '');
    }

    public function removeImage(): void
    {
        $this->settings->set('home_about_image', '');
        $this->home_about_image_path = '';
        $this->home_about_image = null;
    }

    public function render()
    {
        $slides = HeroSlide::where('page', $this->heroSlidePage())->orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.home-settings', compact('slides'))
            ->layout('layouts.admin');
    }
}
