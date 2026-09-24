@extends('layouts.admin')
@section('content')
<h2 class="text-xl font-bold mb-6" style="font-family:var(--font-heading); color: var(--muni-red-dark);">Create Newsletter</h2>
<form method="POST" action="{{ route('admin.newsletters.store') }}" enctype="multipart/form-data" class="bg-white rounded-sm shadow-sm p-6 border space-y-4 max-w-2xl">
@csrf
<div><label class="text-sm font-bold">Title *</label><input type="text" name="title" value="{{ old('title') }}" required class="w-full border-2 rounded-sm px-3 py-2" style="min-height:44px;"></div>
<div><label class="text-sm font-bold">Slug</label><input type="text" name="slug" value="{{ old('slug') }}" class="w-full border-2 rounded-sm px-3 py-2 text-sm"></div>
<div><label class="text-sm font-bold">Description</label><textarea name="description" rows="3" class="w-full border-2 rounded-sm px-3 py-2">{{ old('description') }}</textarea></div>
<div><label class="text-sm font-bold">Publication Year *</label><input type="number" name="publication_year" value="{{ old('publication_year', date('Y')) }}" required class="w-full border-2 rounded-sm px-3 py-2"></div>
<div><label class="text-sm font-bold">Cover Image</label><input type="file" name="cover_image" accept="image/*" class="w-full text-sm"><p class="text-xs text-gray-500">Max 5MB</p></div>
<div><label class="text-sm font-bold">PDF File *</label><input type="file" name="file" accept="application/pdf" required class="w-full text-sm"><p class="text-xs text-gray-500">Max 10MB, PDF only. Stored to storage/app/public/newsletters/</p></div>
<button type="submit" class="btn-muni">Create Newsletter</button>
</form>
@endsection
