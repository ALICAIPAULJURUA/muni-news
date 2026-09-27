@extends('layouts.admin')
@section('content')
<h2 class="text-xl font-bold mb-6" style="font-family:var(--font-heading); color: var(--muni-red-dark);">Create Article</h2>

@if($errors->any())
<div class="bg-red-50 border-l-4 p-4 mb-4 rounded-sm" style="border-color: var(--muni-red);">
    <ul class="text-sm text-red-800 list-disc ms-4">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('admin.articles.store') }}" enctype="multipart/form-data" class="space-y-6">
@csrf
<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-4">
        <div class="bg-white rounded-sm shadow-sm p-6 border">
            <label class="block text-sm font-bold mb-1">Title *</label>
            <input type="text" name="title" value="{{ old('title') }}" required class="w-full border-2 rounded-sm px-3 py-2" style="min-height:44px;" placeholder="Article title">
            <label class="block text-sm font-bold mt-4 mb-1">Slug (auto)</label>
            <input type="text" name="slug" value="{{ old('slug') }}" class="w-full border-2 rounded-sm px-3 py-2 text-sm" placeholder="auto-generated">
            <label class="block text-sm font-bold mt-4 mb-1">Category *</label>
            <select name="category_id" required class="w-full border-2 rounded-sm px-3 py-2 bg-white" style="min-height:44px;">
                <option value="">Select</option>
                @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ old('category_id')==$cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach
            </select>
            <label class="block text-sm font-bold mt-4 mb-1">Summary *</label>
            <textarea name="summary" rows="3" required class="w-full border-2 rounded-sm px-3 py-2">{{ old('summary') }}</textarea>
            <label class="block text-sm font-bold mt-4 mb-1">Content *</label>
            <textarea id="content" name="content" rows="12" class="w-full border-2 rounded-sm px-3 py-2">{{ old('content') }}</textarea>
        </div>

        <div class="bg-white rounded-sm shadow-sm p-6 border">
            <h3 class="font-bold mb-3" style="color: var(--muni-red-dark);">SEO</h3>
            <label class="block text-sm font-bold mb-1">Meta Title (max 100)</label>
            <input type="text" name="meta_title" value="{{ old('meta_title') }}" maxlength="100" class="w-full border-2 rounded-sm px-3 py-2">
            <label class="block text-sm font-bold mt-3 mb-1">Meta Description (max 200)</label>
            <textarea name="meta_description" rows="2" maxlength="200" class="w-full border-2 rounded-sm px-3 py-2">{{ old('meta_description') }}</textarea>
            <label class="block text-sm font-bold mt-3 mb-1">Tags (comma separated)</label>
            <input type="text" name="tags_csv" id="tags_csv" value="{{ old('tags_csv') }}" placeholder="e.g. research, students, innovation" class="w-full border-2 rounded-sm px-3 py-2">
            <div id="tags_hidden"></div>
            <p class="text-xs text-gray-500 mt-1">Tags will be created automatically.</p>
        </div>
    </div>

    <div class="space-y-4">
        <div class="bg-white rounded-sm shadow-sm p-6 border">
            <h3 class="font-bold mb-3" style="color: var(--muni-red-dark);">Publish Settings</h3>
            <label class="block text-sm font-bold mb-1">Published At</label>
            <input type="datetime-local" name="published_at" value="{{ old('published_at') }}" class="w-full border-2 rounded-sm px-3 py-2">
            <div class="mt-3 space-y-2">
                <label class="flex items-center gap-2"><input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} class="rounded"> <span class="text-sm">Published</span></label>
                <label class="flex items-center gap-2"><input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="rounded"> <span class="text-sm">Featured</span></label>
                <label class="flex items-center gap-2"><input type="checkbox" name="is_breaking" value="1" {{ old('is_breaking') ? 'checked' : '' }} class="rounded"> <span class="text-sm">Breaking News</span></label>
            </div>
        </div>

        <div class="bg-white rounded-sm shadow-sm p-6 border">
            <h3 class="font-bold mb-3" style="color: var(--muni-red-dark);">Featured Image</h3>
            <input type="file" name="featured_image" accept="image/*" class="w-full text-sm">
            <p class="text-xs text-gray-500 mt-2">Max 5MB. Allowed: jpeg,png,jpg,gif,webp,svg. Stored to /storage/app/public/articles/</p>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="btn-muni flex-1">Create Article</button>
            <a href="#" onclick="alert('Save as draft first, then use Preview on edit page'); return false;" class="flex-1 text-center px-4 py-3 rounded-sm border-2 font-bold text-sm flex items-center justify-center" style="border-color: var(--muni-gold); color: var(--muni-red-dark); background: #fff; min-height:44px; border-radius:2px;"><i class="fa-solid fa-eye me-1"></i> Preview</a>
        </div>
        <a href="{{ route('admin.articles.index') }}" class="block text-center text-sm underline">Cancel</a>
    </div>
</div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function(){
    // tags handling
    const form = document.querySelector('form');
    form.addEventListener('submit', function(){
        const csv = document.getElementById('tags_csv').value;
        const hidden = document.getElementById('tags_hidden');
        hidden.innerHTML='';
        if(csv){
            csv.split(',').forEach(t=>{
                t=t.trim(); if(!t) return;
                const inp=document.createElement('input'); inp.type='hidden'; inp.name='tags[]'; inp.value=t;
                hidden.appendChild(inp);
            });
        }
    });
});
</script>

@include('admin.partials.tinymce')
@endsection
