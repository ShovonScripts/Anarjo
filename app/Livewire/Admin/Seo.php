<?php

namespace App\Livewire\Admin;

use App\Models\Author;
use App\Models\Blog;
use App\Models\Book;
use App\Models\Category;
use App\Services\SettingsService;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Seo extends Component
{
    public string $tab = 'general';

    public string $seo_meta_title = '';

    public string $seo_meta_description = '';

    public string $seo_og_image = '';

    public string $seo_twitter_handle = '';

    public string $seo_google_analytics_id = '';

    public string $seo_google_tag_manager_id = '';

    public string $seo_facebook_pixel_id = '';

    public string $seo_bing_verification = '';

    public string $seo_yandex_verification = '';

    public string $robots_txt = '';

    public bool $sitemap_include_books = true;

    public bool $sitemap_include_blogs = true;

    public bool $sitemap_include_authors = true;

    public bool $sitemap_include_categories = true;

    protected SettingsService $settings;

    public function boot(SettingsService $settings): void
    {
        $this->settings = $settings;
    }

    public function mount(): void
    {
        $keys = [
            'seo_meta_title', 'seo_meta_description', 'seo_og_image',
            'seo_twitter_handle', 'seo_google_analytics_id', 'seo_google_tag_manager_id',
            'seo_facebook_pixel_id', 'seo_bing_verification', 'seo_yandex_verification',
            'sitemap_include_books', 'sitemap_include_blogs', 'sitemap_include_authors', 'sitemap_include_categories',
        ];

        foreach ($keys as $key) {
            $value = $this->settings->get($key);
            if (property_exists($this, $key)) {
                if (in_array($key, ['sitemap_include_books', 'sitemap_include_blogs', 'sitemap_include_authors', 'sitemap_include_categories'])) {
                    $this->$key = $value === '1' || $value === true;
                } else {
                    $this->$key = $value ?? '';
                }
            }
        }

        $this->robots_txt = $this->settings->get('robots_txt', $this->defaultRobotsTxt());
    }

    public function saveGeneral(): void
    {
        $this->validate([
            'seo_meta_title' => 'nullable|string|max:255',
            'seo_meta_description' => 'nullable|string|max:500',
            'seo_og_image' => 'nullable|string|max:500',
            'seo_twitter_handle' => 'nullable|string|max:50',
            'seo_google_analytics_id' => 'nullable|string|max:50',
            'seo_google_tag_manager_id' => 'nullable|string|max:50',
            'seo_facebook_pixel_id' => 'nullable|string|max:50',
            'seo_bing_verification' => 'nullable|string|max:100',
            'seo_yandex_verification' => 'nullable|string|max:100',
        ]);

        $this->settings->setMultiple([
            'seo_meta_title' => $this->seo_meta_title,
            'seo_meta_description' => $this->seo_meta_description,
            'seo_og_image' => $this->seo_og_image,
            'seo_twitter_handle' => $this->seo_twitter_handle,
            'seo_google_analytics_id' => $this->seo_google_analytics_id,
            'seo_google_tag_manager_id' => $this->seo_google_tag_manager_id,
            'seo_facebook_pixel_id' => $this->seo_facebook_pixel_id,
            'seo_bing_verification' => $this->seo_bing_verification,
            'seo_yandex_verification' => $this->seo_yandex_verification,
        ]);

        $this->dispatch('notify', message: 'SEO settings saved.');
    }

    public function saveSitemap(): void
    {
        $this->settings->setMultiple([
            'sitemap_include_books' => $this->sitemap_include_books ? '1' : '0',
            'sitemap_include_blogs' => $this->sitemap_include_blogs ? '1' : '0',
            'sitemap_include_authors' => $this->sitemap_include_authors ? '1' : '0',
            'sitemap_include_categories' => $this->sitemap_include_categories ? '1' : '0',
        ]);

        Cache::forget('sitemap_counts');

        $this->dispatch('notify', message: 'Sitemap settings saved.');
    }

    public function saveRobots(): void
    {
        $this->settings->set('robots_txt', $this->robots_txt);

        $this->dispatch('notify', message: 'robots.txt saved.');
    }

    public function resetRobots(): void
    {
        $this->robots_txt = $this->defaultRobotsTxt();
        $this->settings->set('robots_txt', $this->robots_txt);

        $this->dispatch('notify', message: 'robots.txt reset to default.');
    }

    public function getSitemapCountsProperty(): array
    {
        return Cache::remember('sitemap_counts', 3600, function () {
            return [
                'books' => Book::where('status', 'published')->count(),
                'blogs' => Blog::where('status', 'published')->count(),
                'authors' => Author::where('status', 'active')->count(),
                'categories' => Category::count(),
            ];
        });
    }

    protected function defaultRobotsTxt(): string
    {
        return "User-agent: *\nAllow: /\n\nSitemap: ".url('sitemap.xml');
    }

    public function render()
    {
        return view('livewire.admin.seo')
            ->layout('layouts.admin');
    }
}
