@extends('layouts.admin')
@section('content')
<h2 class="text-xl font-bold mb-6" style="font-family:var(--font-heading); color: var(--muni-red-dark);">Create Newsletter</h2>

@if($errors->any())
<div class="bg-red-50 border-l-4 p-4 mb-4 rounded-sm" style="border-color: var(--muni-red);">
    <ul class="text-sm text-red-800 list-disc ms-4">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('admin.newsletters.store') }}" enctype="multipart/form-data">
@csrf
<div class="max-w-3xl bg-white rounded-sm shadow-sm p-6 border space-y-5">
    <div>
        <label class="block text-sm font-bold mb-1">Title *</label>
        <input type="text" name="title" value="{{ old('title') }}" required class="w-full border-2 rounded-sm px-3 py-2" style="min-height:44px;" placeholder="Newsletter title">
        @error('title')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-bold mb-1">Featured Image</label>
        <input type="file" name="featured_image" accept="image/*" class="w-full text-sm">
        <p class="text-xs text-gray-500 mt-2">Recommended size: 1200 x 630 pixels. Max 5MB.</p>
        @error('featured_image')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-bold mb-1">Content</label>
        <textarea id="content" name="content" rows="14" class="w-full border-2 rounded-sm px-3 py-2">{{ old('content') }}</textarea>
        @error('content')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} class="rounded">
            <span class="text-sm font-bold">Publish (visible to the public)</span>
        </label>
    </div>

    <div class="flex gap-3 pt-2 border-t border-gray-100">
        <button type="submit" class="btn-muni">Create Newsletter</button>
        <a href="{{ route('admin.newsletters.index') }}" class="btn btn-outline">Cancel</a>
    </div>
</div>
</form>

@include('admin.partials.tinymce')
@endsection