@extends('layouts.admin')
@section('content')
<h2 class="text-xl font-bold mb-6" style="font-family:var(--font-heading); color: var(--muni-red-dark);">Edit Newsletter</h2>

@if($errors->any())
<div class="bg-red-50 border-l-4 p-4 mb-4 rounded-sm" style="border-color: var(--muni-red);">
    <ul class="text-sm text-red-800 list-disc ms-4">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('admin.newsletters.update', $newsletter) }}" enctype="multipart/form-data">
@csrf @method('PUT')
<div class="max-w-3xl bg-white rounded-sm shadow-sm p-6 border space-y-5">
    <div>
        <label class="block text-sm font-bold mb-1">Title *</label>
        <input type="text" name="title" value="{{ old('title', $newsletter->title) }}" required class="w-full border-2 rounded-sm px-3 py-2" style="min-height:44px;">
        @error('title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-bold mb-1">Featured Image</label>
        @if($newsletter->featured_image)
            <img src="{{ asset('storage/' . $newsletter->featured_image) }}" alt="" class="w-full h-40 object-cover rounded-sm mb-2" style="object-position: top;">
        @elseif($newsletter->image())
            <img src="{{ asset('storage/' . $newsletter->image()) }}" alt="" class="w-full h-40 object-cover rounded-sm mb-2" style="object-position: top;">
        @endif
        <input type="file" name="featured_image" accept="image/*" class="w-full text-sm">
        <p class="text-xs text-gray-500 mt-2">Recommended size: 1200 x 630 pixels. Max 5MB. Leave empty to keep the current image.</p>
        @error('featured_image')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-bold mb-1">Content</label>
        <textarea id="content" name="content" rows="14" class="w-full border-2 rounded-sm px-3 py-2">{{ old('content', $newsletter->content) }}</textarea>
        @error('content')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_published" value="1" {{ old('is_published', $newsletter->is_published) ? 'checked' : '' }} class="rounded">
            <span class="text-sm font-bold">Publish (visible to the public)</span>
        </label>
    </div>

    <div class="flex gap-3 pt-2 border-t border-gray-100">
        <button type="submit" class="btn-muni">Update Newsletter</button>
        <a href="{{ route('admin.newsletters.index') }}" class="btn btn-outline">Cancel</a>
    </div>
</div>
</form>

@include('admin.partials.tinymce')
@endsection