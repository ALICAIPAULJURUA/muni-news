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

<form method="POST" action="{{ route('admin.newsletters.update', $newsletter) }}" enctype="multipart/form-data" class="space-y-6">
@csrf @method('PUT')
<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-4">
        <div class="bg-white rounded-sm shadow-sm p-6 border">
            <label class="block text-sm font-bold mb-1">Title *</label>
            <input type="text" name="title" value="{{ old('title', $newsletter->title) }}" required class="w-full border-2 rounded-sm px-3 py-2" style="min-height:44px;">
            <label class="block text-sm font-bold mt-4 mb-1">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $newsletter->slug) }}" class="w-full border-2 rounded-sm px-3 py-2 text-sm">
            <label class="block text-sm font-bold mt-4 mb-1">Summary / Description</label>
            <textarea name="description" rows="3" class="w-full border-2 rounded-sm px-3 py-2">{{ old('description', $newsletter->description) }}</textarea>
            <label class="block text-sm font-bold mt-4 mb-1">Content (TinyMCE)</label>
            <textarea id="content" name="content" rows="14" class="w-full border-2 rounded-sm px-3 py-2">{{ old('content', $newsletter->content) }}</textarea>
        </div>
    </div>

    <div class="space-y-4">
        <div class="bg-white rounded-sm shadow-sm p-6 border">
            <h3 class="font-bold mb-3" style="color: var(--muni-red-dark);">Details</h3>
            <label class="block text-sm font-bold mb-1">Publication Year *</label>
            <input type="number" name="publication_year" value="{{ old('publication_year', $newsletter->publication_year) }}" required class="w-full border-2 rounded-sm px-3 py-2">
            <div class="mt-3">
                <label class="flex items-center gap-2"><input type="checkbox" name="is_published" value="1" {{ old('is_published', $newsletter->is_published) ? 'checked' : '' }} class="rounded"><span class="text-sm">Published (visible to public)</span></label>
            </div>
        </div>

        <div class="bg-white rounded-sm shadow-sm p-6 border">
            <h3 class="font-bold mb-3" style="color: var(--muni-red-dark);">Featured Image</h3>
            @if($newsletter->featured_image)
                <img src="{{ asset('storage/' . $newsletter->featured_image) }}" alt="" class="w-full h-32 object-cover rounded-sm mb-2">
                <p class="text-xs text-gray-500 mb-2">Current featured image.</p>
            @endif
            <input type="file" name="featured_image" accept="image/*" class="w-full text-sm">
            <p class="text-xs text-gray-500 mt-2">Max 5MB. Leave empty to keep the current image.</p>
        </div>

        <div class="bg-white rounded-sm shadow-sm p-6 border">
            <h3 class="font-bold mb-3" style="color: var(--muni-red-dark);">Cover Image (archive)</h3>
            @if($newsletter->cover_image)
                <img src="{{ asset('storage/' . $newsletter->cover_image) }}" alt="" class="w-full h-32 object-cover rounded-sm mb-2">
                <p class="text-xs text-gray-500 mb-2">Current cover image.</p>
            @endif
            <input type="file" name="cover_image" accept="image/*" class="w-full text-sm">
            <p class="text-xs text-gray-500 mt-2">Max 5MB. Leave empty to keep the current image.</p>
        </div>

        <div class="bg-white rounded-sm shadow-sm p-6 border">
            <h3 class="font-bold mb-3" style="color: var(--muni-red-dark);">PDF Attachment (optional)</h3>
            @if($newsletter->file_path)
                <p class="text-sm text-blue-700 mb-1"><i class="fa-solid fa-file-pdf me-1"></i><a href="{{ asset('storage/' . $newsletter->file_path) }}" target="_blank">View current PDF</a></p>
                <p class="text-xs text-gray-500 mb-2">Upload a new file to replace it.</p>
            @endif
            <input type="file" name="file" accept="application/pdf" class="w-full text-sm">
            <p class="text-xs text-gray-500 mt-2">Max 10MB, PDF only.</p>
        </div>

        <button type="submit" class="btn-muni w-full">Update Newsletter</button>
        <a href="{{ route('admin.newsletters.index') }}" class="block text-center text-sm underline">Cancel</a>
    </div>
</div>
</form>

@include('admin.partials.tinymce')
@endsection