@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <nav class="text-sm text-gray-500 mb-4"><a href="{{ url('/') }}" class="hover:text-[var(--muni-red)]">Home</a> <span class="mx-2">/</span> <span style="color: var(--muni-red-dark);">Gallery</span></nav>
    <h1 class="text-2xl font-bold mb-6" style="font-family:var(--font-heading); color: var(--muni-red-dark);"><i class="fa-solid fa-images me-2" style="color: var(--muni-red);"></i>Media Gallery</h1>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" x-data="{ lightbox:false, src:'' }">
        @forelse($media as $item)
        <div class="card-muni cursor-pointer group" @click="lightbox=true; src='{{ asset('storage/' . $item->path) }}'">
            @if(str_starts_with($item->mime_type, 'image/'))
                <img src="{{ asset('storage/' . $item->path) }}" alt="{{ $item->original_name }}" class="group-hover:opacity-90 transition">
            @else
                <div class="w-full flex flex-col items-center justify-center py-8" style="aspect-ratio:16/9; background: var(--color-bg-tertiary);">
                    <i class="fa-solid fa-file text-2xl text-gray-400"></i><span class="text-xs mt-2">{{ $item->extension }}</span>
                </div>
            @endif
            <div class="p-3">
                <p class="text-xs font-semibold truncate">{{ $item->original_name }}</p>
                <p class="text-xs text-gray-500">{{ number_format($item->size/1024,1) }} KB</p>
            </div>
        </div>
        @empty
        <p class="col-span-4 text-center text-gray-500 py-12">No media yet.</p>
        @endforelse

        <!-- Lightbox -->
        <div x-show="lightbox" x-transition class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4" @click="lightbox=false" style="display:none;">
            <img :src="src" alt="Preview" class="max-w-full max-h-[90vh] rounded-sm shadow-lg">
            <button class="absolute top-4 right-4 text-white text-2xl" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
        </div>
    </div>

    <div class="mt-8 flex justify-center">{{ $media->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
