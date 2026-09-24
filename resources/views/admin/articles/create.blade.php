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

        <button type="submit" class="btn-muni w-full">Create Article</button>
        <a href="{{ route('admin.articles.index') }}" class="block text-center text-sm underline">Cancel</a>
    </div>
</div>
</form>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
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
tinymce.init({
    selector: '#content',
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
                if (xhr.status === 403) { reject({ message: 'HTTP Error: ' + xhr.status, remove: true }); return; }
                if (xhr.status < 200 || xhr.status >= 300) { reject('HTTP Error: ' + xhr.status); return; }
                const json = JSON.parse(xhr.responseText);
                if (!json || typeof json.location != 'string') { reject('Invalid JSON: ' + xhr.responseText); return; }
                resolve(json.location);
            };
            xhr.onerror = () => { reject('Image upload failed due to a XHR Transport error. Code: ' + xhr.status); };
            const formData = new FormData();
            formData.append('file', blobInfo.blob(), blobInfo.filename());
            xhr.send(formData);
        });
    },
    extended_valid_elements: 'style[type],script[src|type|defer],div[],span[],article[*]',
    verify_html: false,
    height: 400,
    content_style: 'body { font-family: Source Sans Pro, sans-serif; line-height:1.8; max-width:75ch; }'
});
</script>
@endsection
