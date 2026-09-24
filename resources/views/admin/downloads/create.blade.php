@extends('layouts.admin')
@section('content')
<h2 class="text-xl font-bold mb-6" style="font-family:var(--font-heading); color: var(--muni-red-dark);">Create Download</h2>
<form method="POST" action="{{ route('admin.downloads.store') }}" enctype="multipart/form-data" class="bg-white rounded-sm shadow-sm p-6 border space-y-4 max-w-2xl">
@csrf
<div><label class="text-sm font-bold">Title *</label><input type="text" name="title" value="{{ old('title') }}" required class="w-full border-2 rounded-sm px-3 py-2" style="min-height:44px;"></div>
<div><label class="text-sm font-bold">Slug</label><input type="text" name="slug" value="{{ old('slug') }}" class="w-full border-2 rounded-sm px-3 py-2 text-sm"></div>
<div><label class="text-sm font-bold">Description</label><textarea name="description" rows="3" class="w-full border-2 rounded-sm px-3 py-2">{{ old('description') }}</textarea></div>
<div><label class="text-sm font-bold">Category *</label><input type="text" name="category" value="{{ old('category') }}" required placeholder="e.g. Academic, Finance" class="w-full border-2 rounded-sm px-3 py-2"></div>
<div><label class="text-sm font-bold">File *</label><input type="file" name="file" required class="w-full text-sm"><p class="text-xs text-gray-500">Max 10MB. Stored to storage/app/public/downloads/ Sanitized via Str::slug</p></div>
<button type="submit" class="btn-muni">Create Download</button>
</form>
@endsection
