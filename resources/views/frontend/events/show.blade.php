@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <nav class="text-sm text-gray-500 mb-4"><a href="{{ url('/') }}" class="hover:text-[var(--muni-red)]">Home</a> <span class="mx-2">/</span><a href="{{ route('events.index') }}" class="hover:text-[var(--muni-red)]">Events</a> <span class="mx-2">/</span><span style="color: var(--muni-red-dark);">{{ $event->title }}</span></nav>

    <article class="bg-white rounded-sm shadow-sm border border-gray-200 overflow-hidden">
        @if($event->featured_image)
            <img src="{{ asset('storage/' . $event->featured_image) }}" alt="{{ $event->title }}" class="w-full" style="max-height:400px; object-fit:cover;">
        @endif
        <div class="p-6">
            <div class="flex flex-wrap gap-2 mb-3">
                <span class="badge-muni" style="background: var(--muni-blue);"><i class="fa-solid fa-calendar me-1"></i>{{ $event->event_date->format('F j, Y g:i A') }}</span>
                @if($event->end_date)<span class="badge-muni" style="background: var(--color-bg-tertiary); color: var(--color-text-secondary);">Ends: {{ $event->end_date->format('F j, Y') }}</span>@endif
                @if($event->is_online)<span class="badge-muni" style="background: var(--muni-gold); color: var(--muni-red-dark);">Online</span>@else<span class="badge-muni"><i class="fa-solid fa-location-dot me-1"></i>{{ $event->location }}</span>@endif
            </div>
            <h1 class="text-2xl font-bold mb-4" style="font-family:var(--font-heading); color: var(--muni-red-dark);">{{ $event->title }}</h1>
            <div class="prose max-w-none text-gray-700" style="line-height:1.8;">
                <p>{{ $event->description }}</p>
            </div>
            @if($event->registration_link)
                <a href="{{ $event->registration_link }}" target="_blank" class="btn-muni inline-flex mt-6"><i class="fa-solid fa-up-right-from-square me-2"></i>Register</a>
            @endif
        </div>
    </article>

    @if($related->count())
    <div class="mt-8">
        <h3 class="font-bold mb-4">More Events</h3>
        <div class="grid md:grid-cols-3 gap-4">
            @foreach($related as $rel)
                <a href="{{ route('event.show', $rel->slug) }}" class="card-muni p-4">
                    <p class="text-xs text-gray-500">{{ $rel->event_date->format('M d, Y') }}</p>
                    <h4 class="font-semibold text-sm mt-1 hover:text-[var(--muni-red)]">{{ $rel->title }}</h4>
                </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
