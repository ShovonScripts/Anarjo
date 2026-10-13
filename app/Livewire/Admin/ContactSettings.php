<?php

namespace App\Livewire\Admin;

use App\Services\SettingsService;
use Livewire\Component;

class ContactSettings extends Component
{
    public string $contact_label = '';

    public string $contact_hero_title = '';

    public string $contact_subtitle = '';

    public array $faqs = [];

    public string $new_question = '';

    public string $new_answer = '';

    public $editIndex = null;

    public string $edit_question = '';

    public string $edit_answer = '';

    protected SettingsService $settings;

    public function boot(SettingsService $settings): void
    {
        $this->settings = $settings;
    }

    public function mount(): void
    {
        $this->contact_label = $this->settings->get('contact_label', 'Get in Touch');
        $this->contact_hero_title = $this->settings->get('contact_hero_title', 'Contact Us');
        $this->contact_subtitle = $this->settings->get('contact_subtitle', 'Have a question, feedback, or inquiry? We\'d love to hear from you.');
        $saved = $this->settings->get('contact_faqs', '[]');
        $this->faqs = json_decode($saved, true) ?? [];
    }

    public function save(): void
    {
        $data = [
            'contact_label' => $this->contact_label,
            'contact_hero_title' => $this->contact_hero_title,
            'contact_subtitle' => $this->contact_subtitle,
        ];

        $this->settings->setMultiple($data);

        if (! empty($this->faqs)) {
            $this->settings->set('contact_faqs', json_encode($this->faqs));
        }

        $this->dispatch('settings-saved');
    }

    public function addFaq(): void
    {
        $this->validate([
            'new_question' => 'required|string|max:255',
            'new_answer' => 'required|string',
        ]);

        $this->faqs[] = [
            'question' => $this->new_question,
            'answer' => $this->new_answer,
        ];

        $this->saveFaqs();
        $this->new_question = '';
        $this->new_answer = '';
    }

    public function editFaq($index): void
    {
        $this->editIndex = $index;
        $this->edit_question = $this->faqs[$index]['question'];
        $this->edit_answer = $this->faqs[$index]['answer'];
    }

    public function updateFaq(): void
    {
        $this->validate([
            'edit_question' => 'required|string|max:255',
            'edit_answer' => 'required|string',
        ]);

        $this->faqs[$this->editIndex] = [
            'question' => $this->edit_question,
            'answer' => $this->edit_answer,
        ];

        $this->saveFaqs();
        $this->cancelEdit();
    }

    public function deleteFaq($index): void
    {
        unset($this->faqs[$index]);
        $this->faqs = array_values($this->faqs);
        $this->saveFaqs();
    }

    public function cancelEdit(): void
    {
        $this->editIndex = null;
        $this->edit_question = '';
        $this->edit_answer = '';
    }

    protected function saveFaqs(): void
    {
        $this->settings->set('contact_faqs', json_encode($this->faqs));
        $this->dispatch('faqs-saved');
    }

    public function render()
    {
        return view('livewire.admin.contact-settings')
            ->layout('layouts.admin');
    }
}
