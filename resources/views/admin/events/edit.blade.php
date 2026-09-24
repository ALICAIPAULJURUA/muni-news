@extends('layouts.admin')
@section('content')
<h2 class="text-xl font-bold mb-6" style="font-family:var(--font-heading); color: var(--muni-red-dark);">Edit Event: {{ $event->title }}</h2>
<form method="POST" action="{{ route('admin.events.update', $event) }}" enctype="multipart/form-data" class="bg-white rounded-sm shadow-sm p-6 border space-y-4 max-w-2xl">
@csrf @method('PUT')
<div>
    <label class="text-sm font-bold">Title *</label>
    <input type="text" name="title" value="{{ old('title', $event->title) }}" required class="w-full border-2 rounded-sm px-3 py-2" style="min-height:44px;">
</div>
<div>
    <label class="text-sm font-bold">Slug</label>
    <input type="text" name="slug" value="{{ old('slug', $event->slug) }}" class="w-full border-2 rounded-sm px-3 py-2 text-sm">
</div>
<div>
    <label class="text-sm font-bold">Description *</label>
    <textarea name="description" rows="4" required class="w-full border-2 rounded-sm px-3 py-2">{{ old('description', $event->description) }}</textarea>
</div>
<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="text-sm font-bold">Event Date *</label>
        <input type="datetime-local" name="event_date" value="{{ old('event_date', $event->event_date->format('Y-m-d\TH:i')) }}" required class="w-full border-2 rounded-sm px-3 py-2">
    </div>
    <div>
        <label class="text-sm font-bold">End Date</label>
        <input type="datetime-local" name="end_date" value="{{ old('end_date', $event->end_date?->format('Y-m-d\TH:i')) }}" class="w-full border-2 rounded-sm px-3 py-2">
    </div>
</div>
<div>
    <label class="text-sm font-bold">Location *</label>
    <input type="text" name="location" value="{{ old('location', $event->location) }}" required class="w-full border-2 rounded-sm px-3 py-2">
</div>
<div>
    <label class="text-sm font-bold">Featured Image</label>
    @if($event->featured_image)<img src="{{ asset('storage/'.$event->featured_image) }}" alt="" class="w-32 h-20 object-cover rounded-sm mb-2">@endif
    <input type="file" name="featured_image" accept="image/*" class="w-full text-sm">
</div>
<div class="flex gap-4">
    <label class="flex items-center gap-2"><input type="checkbox" name="is_online" value="1" {{ old('is_online', $event->is_online) ? 'checked' : '' }}> <span class="text-sm">Online Event</span></label>
</div>
<div>
    <label class="text-sm font-bold">Registration Link</label>
    <input type="url" name="registration_link" value="{{ old('registration_link', $event->registration_link) }}" class="w-full border-2 rounded-sm px-3 py-2">
</div>
<button type="submit" class="btn-muni">Update Event</button>
</form>
@endsection
