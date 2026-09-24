@extends('layouts.admin')
@section('content')
<h2 class="text-xl font-bold mb-6" style="font-family:var(--font-heading); color: var(--muni-red-dark);">Edit Article: {{ $article->title }}</h2>

@if($errors->any())
<div class="bg-red-50 border-l-4 p-4 mb-4 rounded-sm" style="border-color: var(--muni-red);">
    <ul class="text-sm text-red-800 list-disc ms-4">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('admin.articles.update', $article) }}" enctype="multipart/form-data" class="space-y-6">
@csrf @method('PUT')
<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-4">
        <div class="bg-white rounded-sm shadow-sm p-6 border">
            <label class="block text-sm font-bold mb-1">Title *</label>
            <input type="text" name="title" value="{{ old('title', $article->title) }}" required class="w-full border-2 rounded-sm px-3 py-2" style="min-height:44px;">
            <label class="block text-sm font-bold mt-4 mb-1">Slug</label>
            <input type="text" name="slug" value="{{ old('slug', $article->slug) }}" class="w-full border-2 rounded-sm px-3 py-2 text-sm">
            <label class="block text-sm font-bold mt-4 mb-1">Category *</label>
            <select name="category_id" required class="w-full border-2 rounded-sm px-3 py-2 bg-white" style="min-height:44px;">
                @foreach($categories as $cat)<option value="{{ $cat->id }}" {{ old('category_id', $article->category_id)==$cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>@endforeach
            </select>
            <label class="block text-sm font-bold mt-4 mb-1">Summary *</label>
            <textarea name="summary" rows="3" required class="w-full border-2 rounded-sm px-3 py-2">{{ old('summary', $article->summary) }}</textarea>
            <label class="block text-sm font-bold mt-4 mb-1">Content *</label>
            <textarea id="content" name="content" rows="12" class="w-full border-2 rounded-sm px-3 py-2">{{ old('content', $article->content) }}</textarea>
        </div>

        <div class="bg-white rounded-sm shadow-sm p-6 border">
            <h3 class="font-bold mb-3" style="color: var(--muni-red-dark);">SEO</h3>
            <label class="block text-sm font-bold mb-1">Meta Title</label>
            <input type="text" name="meta_title" value="{{ old('meta_title', $article->meta_title) }}" maxlength="100" class="w-full border-2 rounded-sm px-3 py-2">
            <label class="block text-sm font-bold mt-3 mb-1">Meta Description</label>
            <textarea name="meta_description" rows="2" maxlength="200" class="w-full border-2 rounded-sm px-3 py-2">{{ old('meta_description', $article->meta_description) }}</textarea>
            <label class="block text-sm font-bold mt-3 mb-1">Tags (comma separated)</label>
            <input type="text" name="tags_csv" id="tags_csv" value="{{ old('tags_csv', $article->tags->pluck('name')->implode(', ')) }}" class="w-full border-2 rounded-sm px-3 py-2">
            <div id="tags_hidden"></div>
        </div>
    </div>

    <div class="space-y-4">
        <div class="bg-white rounded-sm shadow-sm p-6 border">
            <h3 class="font-bold mb-3" style="color: var(--muni-red-dark);">Publish Settings</h3>
            <label class="block text-sm font-bold mb-1">Published At</label>
            <input type="datetime-local" name="published_at" value="{{ old('published_at', $article->published_at?->format('Y-m-d\TH:i')) }}" class="w-full border-2 rounded-sm px-3 py-2">
            <div class="mt-3 space-y-2">
                <label class="flex items-center gap-2"><input type="checkbox" name="is_published" value="1" {{ old('is_published', $article->is_published) ? 'checked' : '' }}> <span class="text-sm">Published</span></label>
                <label class="flex items-center gap-2"><input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $article->is_featured) ? 'checked' : '' }}> <span class="text-sm">Featured</span></label>
                <label class="flex items-center gap-2"><input type="checkbox" name="is_breaking" value="1" {{ old('is_breaking', $article->is_breaking) ? 'checked' : '' }}> <span class="text-sm">Breaking</span></label>
            </div>
            <p class="text-xs text-gray-500 mt-2">Views: {{ $article->views }}</p>
        </div>

        <div class="bg-white rounded-sm shadow-sm p-6 border">
            <h3 class="font-bold mb-3" style="color: var(--muni-red-dark);">Featured Image</h3>
            @if($article->featured_image)<img src="{{ asset('storage/'.$article->featured_image) }}" alt="" class="w-full rounded-sm mb-3" style="aspect-ratio:16/9; object-fit:cover;">@endif
            <input type="file" name="featured_image" accept="image/*" class="w-full text-sm">
            <p class="text-xs text-gray-500 mt-2">Leave empty to keep existing. Max 5MB.</p>
        </div>

        <button type="submit" class="btn-muni w-full">Update Article</button>
        <a href="{{ route('admin.articles.index') }}" class="block text-center text-sm underline">Cancel</a>
    </div>
</div>
</form>

<script src="https://cdn.tiny.cloud/1/x5hqufi1enf2ifu5yp46yk0uqiixtpbk6bzm5u1kh6pthrp0/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
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
tinymce.init({
    selector: '#content',
    license_key: 'gpl',
    plugins: ['advlist','autolink','lists','link','image','charmap','preview','anchor','searchreplace','visualblocks','code','fullscreen','insertdatetime','media','table','wordcount','codesample','paste'],
    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist | link image media table | removeformat | code',
    images_upload_url: '{{ route('admin.upload-image') }}',
    images_upload_handler: function (blobInfo, progress) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '{{ route('admin.upload-image') }}');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            xhr.upload.onprogress = (e) => { progress(e.loaded / e.total * 100); };
            xhr.onload = () => {
                if (xhr.status < 200 || xhr.status >= 300) { reject('HTTP Error: ' + xhr.status); return; }
                const json = JSON.parse(xhr.responseText);
                if (!json || typeof json.location != 'string') { reject('Invalid JSON: ' + xhr.responseText); return; }
                resolve(json.location);
            };
            xhr.onerror = () => { reject('Image upload failed'); };
            const formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            xhr.send(formData);
        });
    },
    extended_valid_elements: 'style[type],script[src|type|defer],div[],span[],article[*]',
    verify_html: false,
    height: 400
});
</script>
@endsection
