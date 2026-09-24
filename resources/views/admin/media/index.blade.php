@extends('layouts.admin')
@section('content')
<h2 class="text-xl font-bold mb-6" style="font-family:var(--font-heading); color: var(--muni-red-dark);">Media Library</h2>

<div class="bg-white rounded-sm shadow-sm p-6 border mb-6">
    <h3 class="font-bold mb-3" style="color: var(--muni-red-dark);">Upload Media</h3>
    <form method="POST" action="{{ route('admin.media.store') }}" enctype="multipart/form-data" class="flex flex-wrap gap-3 items-end">
        @csrf
        <div class="flex-1 min-w-[200px]">
            <label class="text-sm font-bold">Files (max 5MB each)</label>
            <input type="file" name="files[]" multiple required accept="image/*,.pdf,.doc,.docx" class="w-full text-sm border-2 rounded-sm p-2">
        </div>
        <button type="submit" class="btn-muni text-sm">Upload</button>
    </form>
    <p class="text-xs text-gray-500 mt-2">Whitelist MIME validated, sanitized via Str::slug, stored to storage/app/public/media/</p>
</div>

<form method="POST" action="{{ route('admin.media.bulk-destroy') }}" id="bulkForm">
@csrf
<div class="bg-white rounded-sm shadow-sm border overflow-hidden">
    <div class="p-4 border-b flex justify-between items-center">
        <h3 class="font-bold" style="color: var(--muni-red-dark);">All Media</h3>
        <button type="submit" class="text-xs px-3 py-2 rounded-sm bg-red-600 text-white" onclick="return confirm('Delete selected?')">Bulk Delete</button>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 p-4">
        @forelse($media as $item)
        <div class="border rounded-sm overflow-hidden hover:shadow-md">
            <div class="relative">
                <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="absolute top-2 left-2 w-4 h-4">
                @if(str_starts_with($item->mime_type,'image/'))
                    <img src="{{ asset('storage/'.$item->path) }}" alt="{{ $item->original_name }}" class="w-full" style="aspect-ratio:1; object-fit:cover;">
                @else
                    <div class="w-full flex flex-col items-center justify-center" style="aspect-ratio:1; background: var(--color-bg-tertiary);"><i class="fa-solid fa-file text-2xl text-gray-400"></i><span class="text-xs mt-1">{{ $item->extension }}</span></div>
                @endif
            </div>
            <div class="p-2">
                <p class="text-xs font-semibold truncate" title="{{ $item->original_name }}">{{ $item->original_name }}</p>
                <p class="text-xs text-gray-500">{{ number_format($item->size/1024,1) }} KB • {{ $item->uploader->username ?? 'system' }}</p>
                <form method="POST" action="{{ route('admin.media.destroy', $item) }}" class="mt-1" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="text-xs text-red-600 underline">Delete</button></form>
            </div>
        </div>
        @empty
        <p class="col-span-6 text-center text-gray-500 py-12">No media.</p>
        @endforelse
    </div>
    <div class="p-4 border-t">{{ $media->links('pagination::bootstrap-5') }}</div>
</div>
</form>
@endsection
