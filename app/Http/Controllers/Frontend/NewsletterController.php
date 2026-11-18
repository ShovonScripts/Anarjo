<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsletterFormRequest;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;

class NewsletterController extends Controller
{
    public function store(NewsletterFormRequest $request): RedirectResponse
    {
        NewsletterSubscriber::firstOrCreate(
            ['email' => $request->validated('email')],
            ['is_active' => true]
        );

        return back()->with('success', 'Thank you for subscribing to our newsletter!');
    }
}
