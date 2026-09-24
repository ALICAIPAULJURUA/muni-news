<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        $upcoming = Event::where('event_date', '>=', now())
            ->orderBy('event_date')
            ->paginate(9);

        $past = Event::where('event_date', '<', now())
            ->orderByDesc('event_date')
            ->take(6)
            ->get();

        return view('frontend.events.index', compact('upcoming', 'past'));
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
