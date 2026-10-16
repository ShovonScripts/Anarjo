<?php

namespace App\Mail;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactReply extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ContactMessage $message,
        public string $reply,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Re: '.($this->message->subject ?? 'Your Inquiry'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact-reply',
        );
    }
}
