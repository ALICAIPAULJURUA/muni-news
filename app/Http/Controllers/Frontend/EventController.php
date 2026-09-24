<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        $events = \App\Models\Event::orderBy('event_date', 'desc')->paginate(9);
        return view('frontend.events.index', compact('events'));
    }

    public function show(string $slug): View
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        $related = Event::where('id', '!=', $event->id)
            ->orderBy('event_date', 'desc')
            ->take(3)
            ->get();

        $meta_title = $event->title;
        $meta_description = \Illuminate\Support\Str::limit(strip_tags($event->description), 160);

        return view('frontend.events.show', compact('event', 'related', 'meta_title', 'meta_description'));
    }
}
