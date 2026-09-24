@extends('layouts.admin')
@section('content')
<h2 class="text-xl font-bold mb-6" style="font-family:var(--font-heading); color: var(--muni-red-dark);">Edit Download: {{ $download->title }}</h2>
<form method="POST" action="{{ route('admin.downloads.update', $download) }}" enctype="multipart/form-data" class="bg-white rounded-sm shadow-sm p-6 border space-y-4 max-w-2xl">
@csrf @method('PUT')
<div><label class="text-sm font-bold">Title *</label><input type="text" name="title" value="{{ old('title', $download->title) }}" required class="w-full border-2 rounded-sm px-3 py-2" style="min-height:44px;"></div>
<div><label class="text-sm font-bold">Slug</label><input type="text" name="slug" value="{{ old('slug', $download->slug) }}" class="w-full border-2 rounded-sm px-3 py-2 text-sm"></div>
<div><label class="text-sm font-bold">Description</label><textarea name="description" rows="3" class="w-full border-2 rounded-sm px-3 py-2">{{ old('description', $download->description) }}</textarea></div>
<div><label class="text-sm font-bold">Category *</label><input type="text" name="category" value="{{ old('category', $download->category) }}" required class="w-full border-2 rounded-sm px-3 py-2"></div>
<div><label class="text-sm font-bold">File</label><p class="text-xs text-gray-500 mb-1">Current: {{ $download->file_path }} ({{ $download->download_count }} downloads)</p><input type="file" name="file" class="w-full text-sm"><p class="text-xs text-gray-500">Leave empty to keep</p></div>
<button type="submit" class="btn-muni">Update Download</button>
</form>
@endsection
