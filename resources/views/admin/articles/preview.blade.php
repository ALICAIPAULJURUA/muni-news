@extends('layouts.app')

@section('content')
<div class="bg-warning text-dark text-center p-2 fw-bold" style="position: fixed; top: 0; left: 0; right: 0; z-index: 9999; background: #ffde00; color: #1a1a1a; font-weight: 800; padding: 0.5rem; text-align: center; border-bottom: 3px solid #8B0000;">
    ⚠️ PREVIEW MODE: This is a draft and not yet published. <a href="{{ route('admin.articles.edit', $article) }}" class="underline ms-2" style="color: #8B0000;">Back to Edit</a>
</div>
<div style="height: 40px;"></div>

<div class="max-w-7xl mx-auto px-4 py-6">
    <nav aria-label="Breadcrumb" class="text-sm text-gray-500 mb-4">
        <a href="{{ url('/') }}" class="hover:text-[var(--muni-red)]">Home</a> <span class="mx-2">/</span>
        <a href="{{ route('category.show', $article->category->slug) }}" class="hover:text-[var(--muni-red)]">{{ $article->category->name }}</a> <span class="mx-2">/</span>
        <span class="truncate" style="color: var(--muni-red-dark);">{{ \Illuminate\Support\Str::limit($article->title, 50) }}</span>
        <span class="ms-2 badge-muni" style="background: var(--muni-gold); color: var(--muni-red-dark);">Preview</span>
    </nav>

    <div class="grid lg:grid-cols-3 gap-8">
        <article class="lg:col-span-2">
            <header class="mb-6">
                <a href="{{ route('category.show', $article->category->slug) }}" class="badge-muni mb-3 inline-block">{{ $article->category->name }}</a>
                <h1 class="text-3xl lg:text-4xl font-black leading-tight mb-3" style="font-family:var(--font-heading); color: var(--muni-red-dark);">{{ $article->title }}</h1>
                <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500">
                    <span><i class="fa-solid fa-user me-1"></i>{{ $article->author->full_name ?? $article->author->username ?? 'Muni News' }}</span>
                    <span>•</span><span><i class="fa-regular fa-calendar me-1"></i>{{ $article->published_at?->format('F j, Y') ?? $article->created_at->format('F j, Y') }} @if(!$article->is_published)<span class="badge-muni ms-2" style="background: #718096;">Draft</span>@endif</span>
                    <span>•</span><span><i class="fa-solid fa-eye me-1"></i>{{ $article->views }} views</span>
                </div>
            </header>

            @if($article->featured_image)
            <figure class="mb-6">
                <img loading="lazy" src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="w-full rounded-sm shadow-md" style="max-height:500px; object-fit:cover;">
            </figure>
            @endif

            <div class="article-content mx-auto">
                <p class="lead mb-6">{{ $article->summary }}</p>
                <div class="prose max-w-none" style="line-height:1.8;">
                    {!! $article->content !!}
                </div>
            </div>
        </article>

        <aside class="lg:col-span-1">
            <div class="sticky top-24 space-y-6">
                <div class="bg-white rounded-sm shadow-sm border p-4">
                    <h3 class="font-bold mb-2" style="color: var(--muni-red-dark);">Preview Info</h3>
                    <p class="text-sm text-gray-600">This is how the article will appear when published.</p>
                    <ul class="text-xs text-gray-500 mt-3 space-y-1">
                        <li>Status: @if($article->is_published)<span style="color: var(--muni-blue);">Published</span> @else <span style="color: #718096;">Draft</span>@endif</li>
                        <li>Featured: {{ $article->is_featured ? 'Yes' : 'No' }}</li>
                        <li>Breaking: {{ $article->is_breaking ? 'Yes' : 'No' }}</li>
                    </ul>
                    <a href="{{ route('admin.articles.edit', $article) }}" class="btn-muni w-full mt-4 text-center text-sm">Back to Edit</a>
                </div>
            </div>
        </aside>
    </div>
</div>

@push('scripts')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'NewsArticle',
    'headline' => $article->title,
    'description' => $meta_description,
    'image' => $og_image,
    'author' => ['@type' => 'Person', 'name' => $article->author->full_name ?? $article->author->username],
    'publisher' => ['@type' => 'Organization', 'name' => 'Muni University', 'logo' => ['@type' => 'ImageObject', 'url' => asset('assets/images/muni-logo.png')]],
    'datePublished' => $article->published_at?->toIso8601String() ?? $article->created_at->toIso8601String(),
    'dateModified' => $article->updated_at->toIso8601String(),
    'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => url()->current()],
], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
</script>
@endpush
@endsection
