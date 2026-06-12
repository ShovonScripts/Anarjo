<?php

namespace App\Livewire\Admin;

use App\Mail\ContactReply;
use App\Models\ContactMessage;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Mail;

class ContactMessages extends Component
{
    use WithPagination;

    public $selectedMessage;
    public string $reply = '';
    public bool $replyModal = false;

    public function view($id): void
    {
        $this->selectedMessage = ContactMessage::findOrFail($id);
        if (!$this->selectedMessage->read_at) {
            $this->selectedMessage->update(['read_at' => now()]);
        }
        $this->reply = '';
        $this->replyModal = false;
    }

    public function openReply(): void
    {
        $this->replyModal = true;
    }

    public function closeView(): void
    {
        $this->selectedMessage = null;
        $this->reply = '';
        $this->replyModal = false;
    }

    public function sendReply(): void
    {
        $this->validate(['reply' => 'required|string']);

        Mail::to($this->selectedMessage->email, $this->selectedMessage->name)
            ->send(new ContactReply($this->selectedMessage, $this->reply));

        $this->selectedMessage->update([
            'admin_reply' => $this->reply,
            'replied_at' => now(),
        ]);

        $this->reply = '';
        $this->replyModal = false;
        $this->dispatch('reply-sent');
    }

    public function delete($id): void
    {
        ContactMessage::find($id)?->delete();
        if ($this->selectedMessage && $this->selectedMessage->id === (int) $id) {
            $this->selectedMessage = null;
        }
    }

    public function render()
    {
        $messages = ContactMessage::latest()->paginate(20);
        return view('livewire.admin.contact-messages', compact('messages'))
            ->layout('layouts.admin');
    }
}
