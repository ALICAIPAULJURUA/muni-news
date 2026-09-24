@extends('layouts.admin')
@section('content')
<h2 class="text-xl font-bold mb-6" style="font-family:var(--font-heading); color: var(--muni-red-dark);">Edit Newsletter: {{ $newsletter->title }}</h2>
<form method="POST" action="{{ route('admin.newsletters.update', $newsletter) }}" enctype="multipart/form-data" class="bg-white rounded-sm shadow-sm p-6 border space-y-4 max-w-2xl">
@csrf @method('PUT')
<div><label class="text-sm font-bold">Title *</label><input type="text" name="title" value="{{ old('title', $newsletter->title) }}" required class="w-full border-2 rounded-sm px-3 py-2" style="min-height:44px;"></div>
<div><label class="text-sm font-bold">Slug</label><input type="text" name="slug" value="{{ old('slug', $newsletter->slug) }}" class="w-full border-2 rounded-sm px-3 py-2 text-sm"></div>
<div><label class="text-sm font-bold">Description</label><textarea name="description" rows="3" class="w-full border-2 rounded-sm px-3 py-2">{{ old('description', $newsletter->description) }}</textarea></div>
<div><label class="text-sm font-bold">Publication Year *</label><input type="number" name="publication_year" value="{{ old('publication_year', $newsletter->publication_year) }}" required class="w-full border-2 rounded-sm px-3 py-2"></div>
<div><label class="text-sm font-bold">Cover Image</label>@if($newsletter->cover_image)<img src="{{ asset('storage/'.$newsletter->cover_image) }}" alt="" class="w-24 h-32 object-cover rounded-sm mb-2 border">@endif<input type="file" name="cover_image" accept="image/*" class="w-full text-sm"><p class="text-xs text-gray-500">Leave empty to keep</p></div>
<div><label class="text-sm font-bold">PDF File</label><p class="text-xs text-gray-500 mb-1">Current: {{ $newsletter->file_path }} ({{ $newsletter->download_count }} downloads)</p><input type="file" name="file" accept="application/pdf" class="w-full text-sm"><p class="text-xs text-gray-500">Leave empty to keep</p></div>
<button type="submit" class="btn-muni">Update Newsletter</button>
</form>
@endsection
