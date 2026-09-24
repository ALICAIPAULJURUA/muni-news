@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <nav aria-label="Breadcrumb" class="text-sm text-gray-500 mb-4"><a href="{{ url('/') }}" class="hover:text-[var(--muni-red)]">Home</a> <span class="mx-2">/</span> <span style="color: var(--muni-red-dark);">News</span></nav>

    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold" style="font-family:var(--font-heading); color: var(--muni-red-dark);">News</h1>
        <!-- Filter Bar -->
        <form method="GET" action="{{ route('news.index') }}" class="flex flex-wrap gap-2 items-center w-full lg:w-auto">
            <label for="q" class="sr-only">Search</label>
            <input type="search" name="q" id="q" value="{{ request('q') }}" placeholder="Search..." class="border-2 rounded-sm px-3 py-2 text-sm focus:border-[var(--muni-blue)] focus:ring-[var(--muni-blue)]" style="min-height:44px; border-radius:2px;">
            <label for="category" class="sr-only">Category</label>
            <select name="category" id="category" class="border-2 rounded-sm px-3 py-2 text-sm bg-white" style="min-height:44px; border-radius:2px;">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}" {{ request('category')==$cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-muni text-sm">Filter</button>
            @if(request('q') || request('category'))
                <a href="{{ route('news.index') }}" class="text-sm underline hover:text-[var(--muni-red)]">Clear</a>
            @endif
        </form>
    </div>

    @if(request('q'))
        <p class="text-sm text-gray-600 mb-4">Search results for "<strong>{{ request('q') }}</strong>" — {{ $articles->total() }} found</p>
    @endif

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($articles as $article)
        <article class="card-muni">
            <a href="{{ route('article.show', $article->slug) }}">
                @if($article->featured_image)
                    <img src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}">
                @else
                    <div class="w-full flex items-center justify-center" style="aspect-ratio:16/9; background: var(--color-bg-tertiary);"><i class="fa-solid fa-image text-2xl text-gray-400"></i></div>
                @endif
            </a>
            <div class="p-4 flex-1 flex flex-col">
                <div class="flex items-center gap-2 mb-2">
                    <a href="{{ route('category.show', $article->category->slug) }}" class="badge-muni text-xs">{{ $article->category->name ?? 'News' }}</a>
                    <span class="text-xs text-gray-500">{{ $article->published_at?->format('M d, Y') }}</span>
                </div>
                <h2 class="card-title text-base flex-1"><a href="{{ route('article.show', $article->slug) }}" class="hover:text-[var(--muni-red)]">{{ $article->title }}</a></h2>
                <p class="card-excerpt mt-2">{{ $article->summary }}</p>
                <div class="mt-3 flex items-center gap-3 text-xs text-gray-500">
                    <span><i class="fa-solid fa-user me-1"></i>{{ $article->author->full_name ?? 'Muni' }}</span>
                    <span><i class="fa-solid fa-eye me-1"></i>{{ $article->views }}</span>
                </div>
            </div>
        </article>
        @empty
        <div class="col-span-3 text-center py-16 bg-white rounded-sm border border-gray-200">
            <i class="fa-solid fa-inbox text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500">No articles found.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-8 flex justify-center">
        {{ $articles->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
