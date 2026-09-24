@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">All Events</h1>

    @forelse($events as $event)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">{{ $event->title }}</h5>
                <p class="card-text"><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}</p>
                <p class="card-text"><strong>Location:</strong> {{ $event->location }}</p>
                <p class="card-text">{{ Str::limit($event->description, 150) }}</p>
            </div>
        </div>
    @empty
        <div class="alert alert-info">
            No events found in the database.
        </div>
    @endforelse

    <div class="mt-4">
        {{ $events->links() }}
    </div>
</div>
@endsection
