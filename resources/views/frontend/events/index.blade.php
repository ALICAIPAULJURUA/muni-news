@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <nav aria-label="Breadcrumb" class="text-sm text-gray-500 mb-4"><a href="{{ url('/') }}" class="hover:text-[var(--muni-red)]">Home</a> <span class="mx-2">/</span> <span style="color: var(--muni-red-dark);">Events</span></nav>

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold" style="font-family:var(--font-heading); color: var(--muni-red-dark);"><i class="fa-solid fa-calendar-days me-2" style="color: var(--muni-red);"></i>University Events</h1>
        @if($events->total() > 9)
        <span class="text-sm text-gray-600">{{ $events->total() }} events</span>
        @endif
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($events as $event)
        <article class="card-muni">
            <a href="{{ route('event.show', $event->slug) }}">
                @if($event->featured_image)
                    <img loading="lazy" src="{{ asset('storage/' . $event->featured_image) }}" alt="{{ $event->title }}">
                @else
                    <div class="w-full flex items-center justify-center" style="aspect-ratio:16/9; background: var(--color-bg-tertiary);"><i class="fa-solid fa-calendar-days text-2xl text-gray-400"></i></div>
                @endif
            </a>
            <div class="p-4 flex-1 flex flex-col">
                <div class="flex items-center gap-2 mb-2">
                    <span class="badge-muni text-xs"><i class="fa-regular fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</span>
                    @if($event->is_online)
                        <span class="badge-muni" style="background: var(--muni-gold); color: var(--muni-red-dark);">Online</span>
                    @endif
                </div>
                <h2 class="card-title text-base flex-1"><a href="{{ route('event.show', $event->slug) }}" class="hover:text-[var(--muni-red)]">{{ $event->title }}</a></h2>
                <p class="card-excerpt mt-2">{{ \Illuminate\Support\Str::limit($event->description, 120) }}</p>
                <div class="mt-3 flex items-center gap-3 text-xs text-gray-500">
                    <span><i class="fa-solid fa-location-dot me-1"></i>{{ $event->location }}</span>
                </div>
            </div>
        </article>
        @empty
        <div class="col-span-3 text-center py-16 bg-white rounded-sm border border-gray-200">
            <i class="fa-solid fa-calendar-xmark text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500">No events scheduled yet.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-8 flex justify-center">
        {{ $events->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection