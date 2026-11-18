<?php

namespace App\Livewire\Admin;

use App\Services\SettingsService;
use Livewire\Component;
use Mews\Purifier\Facades\Purifier;

class PolicySettings extends Component
{
    public string $privacy_policy = '';

    public string $terms_conditions = '';

    public string $return_refund = '';

    protected SettingsService $settings;

    public function boot(SettingsService $settings): void
    {
        $this->settings = $settings;
    }

    public function mount(): void
    {
        $this->privacy_policy = $this->settings->get('privacy_policy', '');
        $this->terms_conditions = $this->settings->get('terms_conditions', '');
        $this->return_refund = $this->settings->get('return_refund', '');
    }

    public function save(): void
    {
        $data = [
            'privacy_policy' => Purifier::clean($this->privacy_policy),
            'terms_conditions' => Purifier::clean($this->terms_conditions),
            'return_refund' => Purifier::clean($this->return_refund),
        ];

        $this->settings->setMultiple($data);
        $this->dispatch('settings-saved');
    }

    public function render()
    {
        return view('livewire.admin.policy-settings')
            ->layout('layouts.admin');
    }
}
