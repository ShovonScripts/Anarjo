<x-mail::message>
# Hello {{ $message->name }},

{{ $reply }}

---

**Your original message:**
> {{ $message->message }}

<x-mail::button :url="url('/contact')">
Contact Us Again
</x-mail::button>

Best regards,<br>
{{ config('app.name') }}
</x-mail::message>
