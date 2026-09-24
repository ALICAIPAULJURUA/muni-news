@extends('layouts.admin')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold" style="font-family:var(--font-heading); color: var(--muni-red-dark);">Events</h2>
    <a href="{{ route('admin.events.create') }}" class="btn-muni text-sm"><i class="fa-solid fa-plus me-1"></i> New Event</a>
</div>

<div class="bg-white rounded-sm shadow-sm border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-white text-xs uppercase" style="background: var(--muni-red-dark);">
                    <th class="px-4 py-3 text-left" style="border-bottom:3px solid var(--muni-gold);">Title</th>
                    <th class="px-4 py-3 text-left" style="border-bottom:3px solid var(--muni-gold);">Date</th>
                    <th class="px-4 py-3 text-left" style="border-bottom:3px solid var(--muni-gold);">Location</th>
                    <th class="px-4 py-3 text-right" style="border-bottom:3px solid var(--muni-gold);">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($events as $event)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-semibold" style="color: var(--muni-red-dark);">{{ $event->title }} @if($event->is_online)<span class="ms-2 text-xs px-2 py-1 rounded-sm" style="background: var(--muni-gold); color: var(--muni-red-dark);">Online</span>@endif</td>
                    <td class="px-4 py-3 text-xs">{{ $event->event_date->format('M d, Y H:i') }}</td>
                    <td class="px-4 py-3 text-xs">{{ $event->location }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.events.edit', $event) }}" class="text-xs px-2 py-1 rounded-sm" style="background: var(--muni-blue); color:#fff;">Edit</a>
                        <form method="POST" action="{{ route('admin.events.destroy', $event) }}" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE') <button class="text-xs px-2 py-1 rounded-sm bg-red-600 text-white">Delete</button></form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-12 text-center text-gray-500">No events.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t">{{ $events->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
