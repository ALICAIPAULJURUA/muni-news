@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <nav aria-label="Breadcrumb" class="text-sm text-gray-500 mb-4"><a href="{{ url('/') }}" class="hover:text-[var(--muni-red)]">Home</a> <span class="mx-2">/</span><a href="{{ route('news.index') }}" class="hover:text-[var(--muni-red)]">News</a> <span class="mx-2">/</span> <span style="color: var(--muni-red-dark);">{{ $category->name }}</span></nav>

    <div class="border-b-4 pb-4 mb-6" style="border-color: var(--muni-gold); background: var(--color-bg-secondary);" >
        <div class="p-6">
            <span class="badge-muni mb-2 inline-block">{{ $category->name }}</span>
            <h1 class="text-2xl font-bold" style="font-family:var(--font-heading); color: var(--muni-red-dark);">{{ $category->name }}</h1>
            @if($category->description)<p class="text-sm text-gray-600 mt-2">{{ $category->description }}</p>@endif
        </div>
    </div>

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
                <h2 class="card-title text-base flex-1"><a href="{{ route('article.show', $article->slug) }}" class="hover:text-[var(--muni-red)]">{{ $article->title }}</a></h2>
                <p class="card-excerpt mt-2">{{ $article->summary }}</p>
                <div class="mt-3 text-xs text-gray-500">{{ $article->published_at?->format('M d, Y') }} • {{ $article->views }} views</div>
            </div>
        </article>
        @empty
        <div class="col-span-3 text-center py-16 bg-white rounded-sm border"><p class="text-gray-500">No articles in this category.</p></div>
        @endforelse
    </div>
    <div class="mt-8 flex justify-center">{{ $articles->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
