@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <nav class="text-sm text-gray-500 mb-4"><a href="{{ url('/') }}" class="hover:text-[var(--muni-red)]">Home</a> <span class="mx-2">/</span> <span style="color: var(--muni-red-dark);">Newsletters</span></nav>

    <h1 class="text-2xl font-bold mb-6" style="font-family:var(--font-heading); color: var(--muni-red-dark);"><i class="fa-solid fa-file-pdf me-2" style="color: var(--muni-red);"></i>Newsletters</h1>

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Articles Left (2/3) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-sm shadow-sm p-6 border-t-4 mb-6" style="border-color: var(--muni-gold);">
                <p class="text-sm text-gray-600">Browse our archive of university newsletters. Download PDFs to stay informed about campus developments.</p>
            </div>

            <div class="space-y-4">
                @forelse($articles as $article)
                <article class="card-muni flex flex-col sm:flex-row">
                    <a href="{{ route('article.show', $article->slug) }}" class="sm:w-48 flex-shrink-0">
                        @if($article->featured_image)
                            <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="w-full h-32 sm:h-full object-cover">
                        @else
                            <div class="w-full h-32 bg-gray-100 flex items-center justify-center"><i class="fa-solid fa-newspaper text-xl text-gray-400"></i></div>
                        @endif
                    </a>
                    <div class="p-4 flex-1">
                        <span class="badge-muni text-xs">{{ $article->category->name ?? 'News' }}</span>
                        <h3 class="font-bold mt-2"><a href="{{ route('article.show', $article->slug) }}" class="hover:text-[var(--muni-red)]">{{ $article->title }}</a></h3>
                        <p class="card-excerpt mt-1">{{ $article->summary }}</p>
                        <p class="text-xs text-gray-500 mt-2">{{ $article->published_at?->format('M d, Y') }}</p>
                    </div>
                </article>
                @empty
                <p class="text-gray-500">No articles.</p>
                @endforelse
            </div>
        </div>

        <!-- PDF Sidebar Right (1/3) -->
        <aside>
            <div class="bg-white rounded-sm shadow-sm border border-gray-200 sticky top-24">
                <div class="px-4 py-3 border-b-2" style="border-color: var(--muni-gold); background: var(--muni-red-dark);">
                    <h3 class="font-bold text-white text-sm uppercase tracking-wider"><i class="fa-solid fa-download me-2"></i>PDF Archive</h3>
                </div>

                <!-- Year Filter: 2-column button grid (no overflow) -->
                <div class="p-4 border-b border-gray-100">
                    <p class="text-xs font-bold uppercase tracking-wider mb-2" style="color: var(--muni-red-dark);">Filter by Year</p>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('newsletters.index') }}" class="px-3 py-2 rounded-sm text-sm font-semibold text-center border-2 {{ !$selectedYear ? 'text-white' : 'bg-white hover:bg-gray-50' }}" style="{{ !$selectedYear ? 'background: var(--muni-red); border-color: var(--muni-red);' : 'border-color: var(--color-border);' }}">All</a>
                        @foreach($years as $year)
                            <a href="{{ route('newsletters.index', ['year'=>$year]) }}" class="px-3 py-2 rounded-sm text-sm font-semibold text-center border-2 {{ (string)$selectedYear===(string)$year ? 'text-white' : 'bg-white hover:bg-gray-50' }}" style="{{ (string)$selectedYear===(string)$year ? 'background: var(--muni-red); border-color: var(--muni-red);' : 'border-color: var(--color-border);' }}">{{ $year }}</a>
                        @endforeach
                    </div>
                </div>

                <div class="divide-y divide-gray-100 max-h-[600px] overflow-y-auto">
                    @forelse($newsletters as $nl)
                    <div class="p-4 flex gap-3 hover:bg-gray-50">
                        <div class="w-16 h-20 bg-gray-100 rounded-sm overflow-hidden flex-shrink-0 border">
                            @if($nl->cover_image)
                                <img src="{{ asset('storage/' . $nl->cover_image) }}" alt="{{ $nl->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center"><i class="fa-solid fa-file-pdf text-xl" style="color: var(--muni-red);"></i></div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-bold leading-tight line-clamp-2">{{ $nl->title }}</h4>
                            <p class="text-xs text-gray-500 mt-1">{{ $nl->publication_year }} • <i class="fa-solid fa-download me-1"></i>{{ $nl->download_count }} downloads</p>
                            <p class="text-xs text-gray-600 mt-1 line-clamp-2">{{ $nl->description }}</p>
                            <a href="{{ asset('storage/' . $nl->file_path) }}" target="_blank" class="inline-flex items-center gap-1 mt-2 text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-sm" style="background: var(--muni-red); color:#fff;"><i class="fa-solid fa-download"></i> PDF</a>
                        </div>
                    </div>
                    @empty
                    <p class="p-8 text-center text-sm text-gray-500">No newsletters for this year.</p>
                    @endforelse
                </div>

                @if($newsletters->hasPages())
                <div class="p-4 border-t">
                    {{ $newsletters->links('pagination::bootstrap-5') }}
                </div>
                @endif
            </div>
        </aside>
    </div>
</div>
@endsection
