<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\UploadsMedia;
use App\Models\Event;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mews\Purifier\Facades\Purifier;

class EventForm extends Component
{
    use UploadsMedia, WithFileUploads;

    public $event_id;

    public $title;

    public $date;

    public $venue;

    public $description;

    public $image;

    public $existing_image;

    public $status = 'draft';

    public $is_major = false;

    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'date' => 'nullable|date',
            'venue' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'status' => 'required|in:draft,published',
        ];
    }

    public function mount($event = null): void
    {
        if ($event) {
            $event = $event instanceof Event ? $event : Event::where('slug', $event)->firstOrFail();
            $this->event_id = $event->id;
            $this->title = $event->title;
            $this->date = $event->date?->format('Y-m-d');
            $this->venue = $event->venue;
            $this->description = $event->description;
            $this->existing_image = $event->image;
            $this->status = $event->status;
            $this->is_major = $event->is_major;
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'date' => $this->date,
            'venue' => $this->venue,
            'description' => Purifier::clean($this->description),
            'status' => $this->status,
            'is_major' => $this->is_major,
        ];

        if ($this->image) {
            $data['image'] = $this->replaceMedia($this->image, 'events', $this->existing_image);
        } elseif ($this->event_id && ! $this->existing_image) {
            $data['image'] = null;
        }

        Event::updateOrCreate(['id' => $this->event_id], $data);

        session()->flash('message', $this->event_id ? 'Event updated.' : 'Event created.');

        $this->redirect(route('admin.events'), navigate: true);
    }

    public function removeImage()
    {
        $this->image = null;
        $this->existing_image = null;
    }

    public function render()
    {
        return view('livewire.admin.event-form')
            ->layout('layouts.admin');
    }
}
