<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('status', 'published')->orderBy('is_major', 'desc')->latest()->paginate(12);
        return view('frontend.events.index', compact('events'));
    }

    public function show($slug)
    {
        $event = Event::where('slug', $slug)->where('status', 'published')->firstOrFail();
        return view('frontend.events.show', compact('event'));
    }
}
