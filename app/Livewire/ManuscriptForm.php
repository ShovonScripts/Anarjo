<?php

namespace App\Livewire;

use App\Livewire\Concerns\UploadsMedia;
use App\Models\Manuscript;
use Livewire\Component;
use Livewire\WithFileUploads;

class ManuscriptForm extends Component
{
    use UploadsMedia, WithFileUploads;

    public $author_name;

    public $email;

    public $phone;

    public $book_title;

    public $genre;

    public $file;

    public $submitted = false;

    protected function rules(): array
    {
        return [
            'author_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'book_title' => 'required|string|max:255',
            'genre' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ];
    }

    public function submit()
    {
        $this->validate();

        $data = [
            'author_name' => $this->author_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'book_title' => $this->book_title,
            'genre' => $this->genre,
        ];

        if ($this->file) {
            $data['file'] = $this->storeMedia($this->file, 'manuscripts');
        }

        Manuscript::create($data);

        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.manuscript-form');
    }
}
