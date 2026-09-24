@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <nav class="text-sm text-gray-500 mb-4"><a href="{{ url('/') }}" class="hover:text-[var(--muni-red)]">Home</a> <span class="mx-2">/</span> <span style="color: var(--muni-red-dark);">Downloads</span></nav>
    <h1 class="text-2xl font-bold mb-6" style="font-family:var(--font-heading); color: var(--muni-red-dark);"><i class="fa-solid fa-download me-2" style="color: var(--muni-red);"></i>Downloads</h1>

    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('downloads.index') }}" class="px-4 py-2 rounded-sm text-sm font-semibold border-2 {{ !$selectedCategory ? 'text-white' : '' }}" style="{{ !$selectedCategory ? 'background: var(--muni-red); border-color: var(--muni-red);' : 'border-color: var(--color-border);' }}">All</a>
        @foreach($categories as $cat)
            <a href="{{ route('downloads.index', ['category'=>$cat]) }}" class="px-4 py-2 rounded-sm text-sm font-semibold border-2 {{ $selectedCategory==$cat ? 'text-white' : '' }}" style="{{ $selectedCategory==$cat ? 'background: var(--muni-red); border-color: var(--muni-red);' : 'border-color: var(--color-border);' }}">{{ $cat }}</a>
        @endforeach
    </div>

    <div class="bg-white rounded-sm shadow-sm border border-gray-200 divide-y divide-gray-100">
        @forelse($downloads as $dl)
        <div class="p-4 flex items-center gap-4 hover:bg-gray-50">
            <div class="w-12 h-12 rounded-sm flex items-center justify-center flex-shrink-0" style="background: var(--muni-red); color:#fff;"><i class="fa-solid fa-file"></i></div>
            <div class="flex-1 min-w-0">
                <h3 class="font-semibold text-sm">{{ $dl->title }}</h3>
                <p class="text-xs text-gray-600 line-clamp-1">{{ $dl->description }}</p>
                <p class="text-xs text-gray-500 mt-1"><span class="badge-muni text-xs" style="background: var(--muni-blue);">{{ $dl->category }}</span> • {{ $dl->download_count }} downloads</p>
            </div>
            <a href="{{ route('downloads.download', $dl->id) }}" class="btn-muni text-xs shrink-0"><i class="fa-solid fa-download me-1"></i>Download</a>
        </div>
        @empty
        <p class="p-8 text-center text-gray-500">No downloads found.</p>
        @endforelse
    </div>

    <div class="mt-6 flex justify-center">{{ $downloads->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
