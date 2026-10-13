<?php

namespace App\Livewire\Admin;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class MediaManager extends Component
{
    use WithFileUploads, WithPagination;

    public $upload = [];

    public $search = '';

    public $typeFilter = '';

    protected function rules(): array
    {
        return [
            'upload' => 'required|array',
            'upload.*' => 'file|mimes:jpeg,png,jpg,gif,webp,pdf,doc,docx,xls,xlsx,zip|max:10240',
        ];
    }

    protected $messages = [
        'upload.*.mimes' => 'Only images, PDFs, docs, spreadsheets & zip files are allowed.',
        'upload.*.max' => 'Each file must be under 10MB.',
    ];

    public function render()
    {
        $query = Media::query();

        if ($this->search) {
            $query->where('name', 'like', '%'.$this->search.'%');
        }

        if ($this->typeFilter === 'image') {
            $query->where('type', 'like', 'image/%');
        } elseif ($this->typeFilter === 'document') {
            $query->where(function ($q) {
                $q->where('type', 'like', 'application/%')
                    ->orWhere('type', 'like', 'text/%');
            });
        }

        $allMedia = $query->latest()->paginate(24);

        return view('livewire.admin.media-manager', compact('allMedia'))
            ->layout('layouts.admin');
    }

    public function upload()
    {
        $this->validate();

        foreach ($this->upload as $file) {
            $path = $file->store('media', 'public');

            Media::create([
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'disk' => 'public',
            ]);
        }

        $this->upload = [];
        session()->flash('message', 'Files uploaded successfully.');
    }

    public function delete($id)
    {
        $media = Media::findOrFail($id);
        Storage::disk($media->disk)->delete($media->path);
        $media->delete();
        session()->flash('message', 'File deleted.');
    }

    public function deleteMultiple()
    {
        // placeholder for future batch delete
    }
}
