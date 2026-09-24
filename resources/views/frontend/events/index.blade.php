@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <nav class="text-sm text-gray-500 mb-4"><a href="{{ url('/') }}" class="hover:text-[var(--muni-red)]">Home</a> <span class="mx-2">/</span> <span style="color: var(--muni-red-dark);">Events</span></nav>
    <h1 class="text-2xl font-bold mb-6" style="font-family:var(--font-heading); color: var(--muni-red-dark);"><i class="fa-solid fa-calendar-days me-2" style="color: var(--muni-red);"></i>Events</h1>

    <h2 class="font-bold mb-4" style="color: var(--muni-red);">All Events</h2>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
        @forelse($events as $event)
        <article class="card-muni">
            @if($event->featured_image)
                <img loading="lazy" src="{{ asset('storage/' . $event->featured_image) }}" alt="{{ $event->title }}">
            @else
                <div class="w-full flex items-center justify-center" style="aspect-ratio:16/9; background: var(--color-bg-tertiary);"><i class="fa-solid fa-calendar text-2xl text-gray-400"></i></div>
            @endif
            <div class="p-4">
                <div class="flex items-center gap-2 mb-2">
                    <span class="badge-muni text-xs" style="background: var(--muni-blue);">{{ $event->event_date->format('M d, Y') }}</span>
                    @if($event->is_online)<span class="badge-muni text-xs" style="background: var(--muni-gold); color: var(--muni-red-dark);">Online</span>@endif
                </div>
                <h3 class="font-bold"><a href="{{ route('event.show', $event->slug) }}" class="hover:text-[var(--muni-red)]">{{ $event->title }}</a></h3>
                <p class="text-sm text-gray-600 mt-1"><i class="fa-solid fa-location-dot me-1"></i>{{ $event->location }}</p>
                <p class="card-excerpt mt-2">{{ \Illuminate\Support\Str::limit($event->description, 100) }}</p>
            </div>
        </article>
        @empty
        <p>No events found. (Check if database has records)</p>
        @endforelse
    </div>
    <div class="flex justify-center mb-8">{{ $events->links('pagination::bootstrap-5') }}</div>

</div>
@endsection
