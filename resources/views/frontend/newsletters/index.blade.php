@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <nav aria-label="Breadcrumb" class="text-sm text-gray-500 mb-4">
        <a href="{{ url('/') }}" class="hover:text-[var(--muni-red)]">Home</a> <span class="mx-2">/</span>
        <span style="color: var(--muni-red-dark);">Newsletters</span>
    </nav>

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold" style="font-family:var(--font-heading); color: var(--muni-red-dark);"><i class="fa-solid fa-file-pdf me-2" style="color: var(--muni-red);"></i>Newsletters</h1>
        <!-- Year Filter -->
        <form method="GET" action="{{ route('newsletters.index') }}" class="flex flex-wrap gap-2 items-center">
            <select name="year" onchange="this.form.submit()" class="border-2 rounded-sm px-3 py-2 text-sm bg-white" style="min-height:44px; border-radius:2px;">
                <option value="">All Years</option>
                @foreach($years as $year)
                    <option value="{{ $year }}" {{ (string)$selectedYear===(string)$year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
            @if($selectedYear)
                <a href="{{ route('newsletters.index') }}" class="text-sm underline hover:text-[var(--muni-red)]">Clear</a>
            @endif
        </form>
    </div>

    <p class="text-sm text-gray-600 mb-6">Browse the Muni University newsletter archive. Read editions online or download the PDF version where available.</p>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($newsletters as $nl)
        <article class="card-muni">
            <a href="{{ route('newsletters.show', $nl->slug) }}">
                @if($nl->image())
                    <img loading="lazy" src="{{ asset('storage/' . $nl->image()) }}" alt="{{ $nl->title }}">
                @else
                    <div class="w-full flex items-center justify-center" style="aspect-ratio:16/9; background: var(--color-bg-tertiary);"><i class="fa-solid fa-file-pdf text-2xl text-gray-400"></i></div>
                @endif
            </a>
            <div class="p-4 flex-1 flex flex-col">
                <div class="flex items-center gap-2 mb-2">
                    <span class="badge-muni text-xs" style="background: var(--muni-gold); color: var(--muni-red-dark);">Newsletter</span>
                    <span class="text-xs text-gray-500">{{ $nl->created_at?->format('M d, Y') }}</span>
                </div>
                <h2 class="card-title text-base flex-1"><a href="{{ route('newsletters.show', $nl->slug) }}" class="hover:text-[var(--muni-red)]">{{ $nl->title }}</a></h2>
                <p class="card-excerpt mt-2">{{ Str::limit(strip_tags($nl->content ?: $nl->description ?: ''), 120) }}</p>
                <div class="mt-3 flex items-center gap-3 text-xs text-gray-500">
                    <span><i class="fa-regular fa-calendar me-1"></i>{{ $nl->publication_year }}</span>
                    @if($nl->file_path)
                        <span><i class="fa-solid fa-download me-1"></i>{{ $nl->download_count }} downloads</span>
                    @endif
                </div>
            </div>
        </article>
        @empty
        <div class="col-span-3 text-center py-16 bg-white rounded-sm border border-gray-200">
            <i class="fa-solid fa-file-invoice text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500">No newsletters found{{ $selectedYear ? ' for this year' : '' }}.</p>
        </div>
        @endforelse
    </div>

    @if($newsletters->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $newsletters->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection