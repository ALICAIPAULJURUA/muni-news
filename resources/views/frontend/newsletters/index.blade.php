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

    @forelse($newsletters as $nl)
    <article class="card-muni flex flex-col sm:flex-row mb-4">
        <a href="{{ route('newsletters.show', $nl->slug) }}" class="sm:w-56 flex-shrink-0">
            @if($nl->image())
                <img loading="lazy" src="{{ asset('storage/' . $nl->image()) }}" alt="{{ $nl->title }}" class="w-full h-40 sm:h-full object-cover" style="object-position: top;">
            @else
                <div class="w-full h-40 bg-gray-100 flex items-center justify-center"><i class="fa-solid fa-file-invoice text-3xl" style="color: var(--muni-red);"></i></div>
            @endif
        </a>
        <div class="p-5 flex-1">
            <span class="badge-muni text-xs" style="background: var(--muni-gold); color: var(--muni-red-dark);">University Newsletter</span>
            <h2 class="text-lg font-bold mt-2" style="font-family:var(--font-heading); color: var(--muni-red-dark);">
                <a href="{{ route('newsletters.show', $nl->slug) }}" class="hover:text-[var(--muni-red)]">{{ $nl->title }}</a>
            </h2>
            @if($nl->description)
                <p class="card-excerpt mt-1 line-clamp-3">{{ $nl->description }}</p>
            @endif
            <p class="text-xs text-gray-500 mt-2">
                {{ $nl->publication_year }} • <i class="fa-regular fa-calendar me-1"></i>{{ optional($nl->created_at)->format('M d, Y') }}
                • <i class="fa-solid fa-download me-1"></i>{{ $nl->download_count }} downloads
            </p>
            <div class="flex flex-wrap gap-2 mt-3">
                <a href="{{ route('newsletters.show', $nl->slug) }}" class="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-sm" style="background: var(--muni-blue); color:#fff;"><i class="fa-solid fa-book-open"></i> Read</a>
                @if($nl->file_path)
                <a href="{{ route('newsletters.download', $nl) }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-sm" style="background: var(--muni-red); color:#fff;"><i class="fa-solid fa-download"></i> PDF</a>
                @endif
            </div>
        </div>
    </article>
    @empty
    <div class="bg-white rounded-sm border border-gray-200 py-16 text-center">
        <i class="fa-solid fa-file-invoice text-4xl text-gray-300 mb-3"></i>
        <p class="text-gray-500">No newsletters found{{ $selectedYear ? ' for this year' : '' }}.</p>
    </div>
    @endforelse

    @if($newsletters->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $newsletters->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection