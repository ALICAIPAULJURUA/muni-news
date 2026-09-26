@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <!-- Hero Grid -->
    @if($featuredArticle)
    <section class="mb-8" aria-label="Featured Stories">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4" style="grid-template-columns: lg(2fr 1fr);">
            <!-- Large Featured (2fr) -->
            <div class="lg:col-span-2">
                <article class="card-muni h-full">
                    <a href="{{ route('article.show', $featuredArticle->slug) }}" class="block">
                        @if($featuredArticle->featured_image)
                            <img loading="lazy" src="{{ asset('storage/' . $featuredArticle->featured_image) }}" alt="{{ $featuredArticle->title }}" class="w-full" style="aspect-ratio:16/9; object-fit:cover;">
                        @else
                            <div class="w-full flex items-center justify-center" style="aspect-ratio:16/9; background: var(--color-bg-tertiary);"><i class="fa-solid fa-image text-3xl text-gray-400"></i></div>
                        @endif
                    </a>
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="badge-muni badge-category">{{ $featuredArticle->category->name ?? 'News' }}</span>
                            @if($featuredArticle->is_breaking)<span class="badge-muni" style="background: var(--muni-gold); color: var(--muni-red-dark);">Breaking</span>@endif
                            <span class="text-xs text-gray-500"><i class="fa-regular fa-clock me-1"></i>{{ $featuredArticle->published_at?->diffForHumans() ?? $featuredArticle->created_at->diffForHumans() }}</span>
                        </div>
                        <h2 class="text-2xl font-bold leading-tight" style="font-family:var(--font-heading);"><a href="{{ route('article.show', $featuredArticle->slug) }}" class="hover:text-[var(--muni-red)]">{{ $featuredArticle->title }}</a></h2>
                        <p class="card-excerpt mt-2 flex-1">{{ $featuredArticle->summary }}</p>
                        <div class="mt-3 flex items-center gap-2 text-xs text-gray-500">
                            <span><i class="fa-solid fa-user me-1"></i>{{ $featuredArticle->author->full_name ?? 'Muni News' }}</span>
                            <span>•</span><span><i class="fa-solid fa-eye me-1"></i>{{ $featuredArticle->views }} views</span>
                        </div>
                    </div>
                </article>
            </div>
            <!-- Stacked Secondary (1fr) -->
            <div class="flex flex-col gap-4">
                @forelse($secondaryArticles as $sec)
                <article class="card-muni flex-1">
                    <a href="{{ route('article.show', $sec->slug) }}" class="block">
                        @if($sec->featured_image)
                            <img loading="lazy" src="{{ asset('storage/' . $sec->featured_image) }}" alt="{{ $sec->title }}" class="w-full" style="aspect-ratio:16/9;">
                        @else
                            <div class="w-full flex items-center justify-center" style="aspect-ratio:16/9; background: var(--color-bg-tertiary);"><i class="fa-solid fa-image text-xl text-gray-400"></i></div>
                        @endif
                    </a>
                    <div class="p-4">
                        <span class="badge-muni badge-category text-xs">{{ $sec->category->name ?? 'News' }}</span>
                        <h3 class="card-title mt-2 text-base"><a href="{{ route('article.show', $sec->slug) }}" class="hover:text-[var(--muni-red)]">{{ $sec->title }}</a></h3>
                        <p class="text-xs text-gray-500 mt-1">{{ $sec->published_at?->format('M d, Y') }}</p>
                    </div>
                </article>
                @empty
                <div class="card-muni p-8 text-center text-gray-500">No secondary features</div>
                @endforelse
            </div>
        </div>
    </section>
    @endif

    <!-- Latest News -->
    <section class="mb-10">
        <div class="flex items-center justify-between mb-4 border-b-2 pb-2" style="border-color: var(--muni-gold);">
            <h2 class="text-xl font-bold" style="font-family:var(--font-heading); color: var(--muni-red-dark);"><i class="fa-solid fa-newspaper me-2" style="color: var(--muni-red);"></i> Latest News</h2>
            <a href="{{ route('news.index') }}" class="text-sm font-semibold hover:text-[var(--muni-red)]">View all <i class="fa-solid fa-arrow-right ms-1"></i></a>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            @forelse($latestNews as $article)
            <article class="card-muni">
                <a href="{{ route('article.show', $article->slug) }}">
                    @if($article->featured_image)
                        <img loading="lazy" src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}">
                    @else
                        <div class="w-full flex items-center justify-center" style="aspect-ratio:16/9; background: var(--color-bg-tertiary);"><i class="fa-solid fa-image text-2xl text-gray-400"></i></div>
                    @endif
                </a>
                <div class="p-4 flex-1 flex flex-col">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="badge-muni text-xs">{{ $article->category->name ?? 'News' }}</span>
                        <span class="text-xs text-gray-500">{{ $article->published_at?->format('M d, Y') }}</span>
                    </div>
                    <h3 class="card-title text-base flex-1"><a href="{{ route('article.show', $article->slug) }}" class="hover:text-[var(--muni-red)]">{{ $article->title }}</a></h3>
                    <p class="card-excerpt mt-2">{{ $article->summary }}</p>
                    <div class="mt-3 text-xs text-gray-500"><i class="fa-solid fa-eye me-1"></i>{{ $article->views }} views</div>
                </div>
            </article>
            @empty
            <p class="col-span-3 text-center text-gray-500 py-12">No news yet.</p>
            @endforelse
        </div>
    </section>

    <!-- Latest Newsletters -->
    @if($latestNewsletters->count())
    <section class="mb-10">
        <div class="flex items-center justify-between mb-4 border-b-2 pb-2" style="border-color: var(--muni-gold);">
            <h2 class="text-xl font-bold" style="font-family:var(--font-heading); color: var(--muni-red-dark);"><i class="fa-solid fa-file-pdf me-2" style="color: var(--muni-red);"></i> Latest Newsletters</h2>
            <a href="{{ route('newsletters.index') }}" class="text-sm font-semibold hover:text-[var(--muni-red)]">All newsletters <i class="fa-solid fa-arrow-right ms-1"></i></a>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            @foreach($latestNewsletters as $nl)
            <article class="card-muni">
                <a href="{{ route('newsletters.show', $nl->slug) }}">
                    @if($nl->image())
                        <img loading="lazy" src="{{ asset('storage/' . $nl->image()) }}" alt="{{ $nl->title }}" class="w-full" style="aspect-ratio:16/9; object-fit:cover;">
                    @else
                        <div class="w-full flex items-center justify-center" style="aspect-ratio:16/9; background: var(--color-bg-tertiary);"><i class="fa-solid fa-file-pdf text-3xl" style="color: var(--muni-red);"></i></div>
                    @endif
                </a>
                <div class="p-4 flex-1 flex flex-col">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="badge-muni text-xs" style="background: var(--muni-gold); color: var(--muni-red-dark);">Newsletter</span>
                        <span class="text-xs text-gray-500">{{ $nl->publication_year }}</span>
                    </div>
                    <h3 class="card-title text-base flex-1"><a href="{{ route('newsletters.show', $nl->slug) }}" class="hover:text-[var(--muni-red)]">{{ $nl->title }}</a></h3>
                    <p class="card-excerpt mt-2">{{ $nl->description }}</p>
                    <div class="mt-3 text-xs text-gray-500">
                        <i class="fa-solid fa-download me-1"></i>{{ $nl->download_count }} downloads
                        @if($nl->file_path) • <a href="{{ route('newsletters.download', $nl) }}" target="_blank" class="font-bold hover:text-[var(--muni-red)]"><i class="fa-solid fa-file-pdf me-1"></i>PDF</a>@endif
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </section>
    @endif

    <!-- Category Highlights - Horizontal scrolling -->
    @foreach($categories as $cat)
        @if($cat->articles->count())
        <section class="mb-8">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-lg" style="font-family:var(--font-heading); color: var(--muni-red-dark);">{{ $cat->name }}</h3>
                <a href="{{ route('category.show', $cat->slug) }}" class="text-xs font-bold uppercase tracking-wider hover:text-[var(--muni-red)]">More <i class="fa-solid fa-chevron-right ms-1"></i></a>
            </div>
            <div class="flex gap-4 overflow-x-auto pb-3 snap-x snap-mandatory" style="scrollbar-width: thin;">
                @foreach($cat->articles as $art)
                <article class="card-muni snap-start flex-shrink-0" style="width: 300px;">
                    <a href="{{ route('article.show', $art->slug) }}">
                        @if($art->featured_image)
                            <img loading="lazy" src="{{ asset('storage/' . $art->featured_image) }}" alt="{{ $art->title }}">
                        @else
                            <div class="w-full flex items-center justify-center" style="aspect-ratio:16/9; background: var(--color-bg-tertiary);"><i class="fa-solid fa-image"></i></div>
                        @endif
                    </a>
                    <div class="p-3">
                        <h4 class="card-title text-sm"><a href="{{ route('article.show', $art->slug) }}" class="hover:text-[var(--muni-red)]">{{ $art->title }}</a></h4>
                        <p class="text-xs text-gray-500 mt-1">{{ $art->published_at?->format('M d') }} • {{ $art->views }} views</p>
                    </div>
                </article>
                @endforeach
            </div>
        </section>
        @endif
    @endforeach
</div>
@endsection
