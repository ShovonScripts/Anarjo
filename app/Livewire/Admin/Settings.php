<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\UploadsMedia;
use App\Services\SettingsService;
use Livewire\Component;
use Livewire\WithFileUploads;

class Settings extends Component
{
    use UploadsMedia, WithFileUploads;

    public string $tab = 'general';

    public string $site_name = '';

    public string $site_tagline = '';

    public string $site_description = '';

    public string $currency_symbol = '';

    public string $currency_code = '';

    public $header_logo;

    public $footer_logo;

    public $favicon;

    public string $header_logo_path = '';

    public string $footer_logo_path = '';

    public string $favicon_path = '';

    public string $contact_address = '';

    public string $contact_phone = '';

    public string $contact_email = '';

    public string $social_facebook = '';

    public string $social_twitter = '';

    public string $social_instagram = '';

    public string $social_linkedin = '';

    public string $social_youtube = '';

    public string $mail_driver = '';

    public string $mail_host = '';

    public string $mail_port = '';

    public string $mail_encryption = '';

    public string $mail_from_address = '';

    public string $mail_from_name = '';

    public bool $payment_bkash_sandbox = true;

    public bool $payment_sslcommerz_sandbox = true;

    public bool $pm_cod = true;

    public bool $pm_bkash = true;

    public bool $pm_sslcommerz = true;

    public string $shipping_flat_rate_inside = '';

    public string $shipping_flat_rate_outside = '';

    public string $shipping_free_threshold = '';

    protected SettingsService $settings;

    public function boot(SettingsService $settings): void
    {
        $this->settings = $settings;
    }

    public function mount(): void
    {
        $keys = [
            'site_name', 'site_tagline', 'site_description', 'currency_symbol', 'currency_code',
            'header_logo', 'footer_logo', 'favicon',
            'contact_address', 'contact_phone', 'contact_email',
            'social_facebook', 'social_twitter', 'social_instagram', 'social_linkedin', 'social_youtube',
            'mail_driver', 'mail_host', 'mail_port', 'mail_encryption',
            'mail_from_address', 'mail_from_name',
            'payment_bkash_sandbox', 'payment_sslcommerz_sandbox',
            'pm_cod', 'pm_bkash', 'pm_sslcommerz',
            'payment_bkash_app_key', 'payment_bkash_app_secret', 'payment_bkash_username', 'payment_bkash_password',
            'payment_sslcommerz_store_id', 'payment_sslcommerz_store_passwd',
            'shipping_flat_rate_inside', 'shipping_flat_rate_outside', 'shipping_free_threshold',

        ];

        foreach ($keys as $key) {
            $value = $this->settings->get($key);
            if (property_exists($this, $key)) {
                if (in_array($key, ['payment_sslcommerz_sandbox', 'payment_bkash_sandbox', 'pm_cod', 'pm_bkash', 'pm_sslcommerz'])) {
                    $this->$key = $value === '1' || $value === true;
                } else {
                    $this->$key = $value ?? '';
                }
            }
        }

        $this->header_logo_path = $this->settings->get('header_logo', '');
        $this->footer_logo_path = $this->settings->get('footer_logo', '');
        $this->favicon_path = $this->settings->get('favicon', '');
    }

    public function save(): void
    {
        $data = [];

        if ($this->tab === 'general') {
            $data = [
                'site_name' => $this->site_name,
                'site_tagline' => $this->site_tagline,
                'site_description' => $this->site_description,
                'currency_symbol' => $this->currency_symbol,
                'currency_code' => $this->currency_code,
            ];
        } elseif ($this->tab === 'logo') {
            $this->validate([
                'header_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'footer_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:1024',
            ]);

            if ($this->header_logo && is_object($this->header_logo)) {
                $data['header_logo'] = $this->replaceMedia($this->header_logo, 'settings', $this->header_logo_path);
            }
            if ($this->footer_logo && is_object($this->footer_logo)) {
                $data['footer_logo'] = $this->replaceMedia($this->footer_logo, 'settings', $this->footer_logo_path);
            }
            if ($this->favicon && is_object($this->favicon)) {
                $data['favicon'] = $this->replaceMedia($this->favicon, 'settings', $this->favicon_path);
            }
        } elseif ($this->tab === 'contact') {
            $data = [
                'contact_address' => $this->contact_address,
                'contact_phone' => $this->contact_phone,
                'contact_email' => $this->contact_email,
                'social_facebook' => $this->social_facebook,
                'social_twitter' => $this->social_twitter,
                'social_instagram' => $this->social_instagram,
                'social_linkedin' => $this->social_linkedin,
                'social_youtube' => $this->social_youtube,
            ];
        } elseif ($this->tab === 'email') {
            // SMTP credentials are managed exclusively via environment (.env) and must
            // never be persisted to the database.
            $data = [
                'mail_driver' => $this->mail_driver,
                'mail_host' => $this->mail_host,
                'mail_port' => $this->mail_port,
                'mail_encryption' => $this->mail_encryption,
                'mail_from_address' => $this->mail_from_address,
                'mail_from_name' => $this->mail_from_name,
            ];
        } elseif ($this->tab === 'payment') {
            $data = [
                'payment_bkash_sandbox' => $this->payment_bkash_sandbox ? '1' : '0',
                'payment_sslcommerz_sandbox' => $this->payment_sslcommerz_sandbox ? '1' : '0',
                'pm_cod' => $this->pm_cod ? '1' : '0',
                'pm_bkash' => $this->pm_bkash ? '1' : '0',
                'pm_sslcommerz' => $this->pm_sslcommerz ? '1' : '0',
            ];
        } elseif ($this->tab === 'shipping') {
            $data = [
                'shipping_flat_rate_inside' => $this->shipping_flat_rate_inside,
                'shipping_flat_rate_outside' => $this->shipping_flat_rate_outside,
                'shipping_free_threshold' => $this->shipping_free_threshold,
            ];
        }

        if (! empty($data)) {
            $this->settings->setMultiple($data);
        }

        $this->dispatch('settings-saved');
    }

    public function removeHeaderLogo(): void
    {
        $this->settings->set('header_logo', '');
        $this->header_logo_path = '';
        $this->header_logo = null;
    }

    public function removeFooterLogo(): void
    {
        $this->settings->set('footer_logo', '');
        $this->footer_logo_path = '';
        $this->footer_logo = null;
    }

    public function removeFavicon(): void
    {
        $this->settings->set('favicon', '');
        $this->favicon_path = '';
        $this->favicon = null;
    }

    public function render()
    {
        return view('livewire.admin.settings')->layout('layouts.admin');
    }
}
