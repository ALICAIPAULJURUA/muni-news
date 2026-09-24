@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">
    <nav aria-label="Breadcrumb" class="text-sm text-gray-500 mb-4">
        <a href="{{ url('/') }}" class="hover:text-[var(--muni-red)]">Home</a> <span class="mx-2">/</span>
        <a href="{{ route('category.show', $article->category->slug) }}" class="hover:text-[var(--muni-red)]">{{ $article->category->name }}</a> <span class="mx-2">/</span>
        <span class="truncate" style="color: var(--muni-red-dark);">{{ \Illuminate\Support\Str::limit($article->title, 50) }}</span>
    </nav>

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Main Content 65% (2/3) -->
        <article class="lg:col-span-2">
            <!-- Immersive Header -->
            <header class="mb-6">
                <a href="{{ route('category.show', $article->category->slug) }}" class="badge-muni mb-3 inline-block">{{ $article->category->name }}</a>
                <h1 class="text-3xl lg:text-4xl font-black leading-tight mb-3" style="font-family:var(--font-heading); color: var(--muni-red-dark);">{{ $article->title }}</h1>
                <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500">
                    <span><i class="fa-solid fa-user me-1"></i>{{ $article->author->full_name ?? $article->author->username ?? 'Muni News' }}</span>
                    <span>•</span><span><i class="fa-regular fa-calendar me-1"></i>{{ $article->published_at?->format('F j, Y') ?? $article->created_at->format('F j, Y') }}</span>
                    <span>•</span><span><i class="fa-solid fa-eye me-1"></i>{{ $article->views }} views</span>
                    @if($article->is_breaking)<span class="badge-muni" style="background: var(--muni-gold); color: var(--muni-red-dark);">Breaking</span>@endif
                </div>
            </header>

            @if($article->featured_image)
            <figure class="mb-6">
                <img loading="lazy" src="{{ asset('storage/' . $article->featured_image) }}" alt="{{ $article->title }}" class="w-full rounded-sm shadow-md" style="max-height:500px; object-fit:cover;">
                <figcaption class="text-xs text-gray-500 mt-2 text-center">{{ $article->title }}</figcaption>
            </figure>
            @endif

            <div class="article-content mx-auto">
                <p class="lead mb-6">{{ $article->summary }}</p>
                <div class="prose max-w-none" style="line-height:1.8;">
                    {!! $article->content !!}
                </div>

                <!-- Tags -->
                @if($article->tags->count())
                <div class="mt-8 pt-4 border-t border-gray-200">
                    <h3 class="text-sm font-bold uppercase tracking-wider mb-2" style="color: var(--muni-red-dark);"><i class="fa-solid fa-tags me-2"></i>Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($article->tags as $tag)
                            <span class="px-3 py-1 rounded-sm text-xs font-semibold border" style="border-color: var(--color-border); background: var(--color-bg-secondary);">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Social Sharing -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <h3 class="text-sm font-bold uppercase tracking-wider mb-3" style="color: var(--muni-red-dark);">Share this article</h3>
                    @php $shareUrl = urlencode(url()->current()); $shareTitle = urlencode($article->title); @endphp
                    <div class="flex flex-wrap gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" class="w-10 h-10 rounded-sm flex items-center justify-center text-white hover:opacity-90" style="background:#1877F2; min-width:44px;min-height:44px;" aria-label="Share on Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" class="w-10 h-10 rounded-sm flex items-center justify-center text-white hover:opacity-90" style="background:#000; min-width:44px;"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" class="w-10 h-10 rounded-sm flex items-center justify-center text-white hover:opacity-90" style="background:#0A66C2; min-width:44px;"><i class="fa-brands fa-linkedin-in"></i></a>
                        <a href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}" target="_blank" class="w-10 h-10 rounded-sm flex items-center justify-center text-white hover:opacity-90" style="background:#25D366; min-width:44px;"><i class="fa-brands fa-whatsapp"></i></a>
                        <a href="mailto:?subject={{ $shareTitle }}&body={{ $shareUrl }}" class="w-10 h-10 rounded-sm flex items-center justify-center text-white hover:opacity-90" style="background: var(--muni-red); min-width:44px;"><i class="fa-solid fa-envelope"></i></a>
                    </div>
                </div>
            </div>
        </article>

        <!-- Sidebar 35% sticky -->
        <aside class="lg:col-span-1">
            <div class="sticky top-24 space-y-6">
                <!-- Related Articles -->
                <div class="bg-white rounded-sm shadow-sm border border-gray-200">
                    <div class="px-4 py-3 border-b-2 flex items-center gap-2" style="border-color: var(--muni-gold); background: var(--muni-red-dark);">
                        <i class="fa-solid fa-link text-white"></i><h3 class="font-bold text-white text-sm uppercase tracking-wider">Related Articles</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse($related as $rel)
                        <a href="{{ route('article.show', $rel->slug) }}" class="flex gap-3 p-4 hover:bg-gray-50">
                            <div class="w-20 h-16 bg-gray-200 rounded-sm overflow-hidden flex-shrink-0">
                                @if($rel->featured_image)
                                    <img loading="lazy" src="{{ asset('storage/' . $rel->featured_image) }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center"><i class="fa-solid fa-image text-gray-400"></i></div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold line-clamp-2 leading-tight hover:text-[var(--muni-red)]">{{ $rel->title }}</h4>
                                <p class="text-xs text-gray-500 mt-1">{{ $rel->category->name ?? '' }} • {{ $rel->published_at?->format('M d') }}</p>
                            </div>
                        </a>
                        @empty
                        <p class="p-6 text-sm text-gray-500">No related articles.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Newsletter Signup -->
                <div class="rounded-sm p-6 text-white" style="background: var(--muni-red);">
                    <h3 class="font-bold" style="font-family:var(--font-heading);"><i class="fa-solid fa-envelope me-2"></i>Newsletter</h3>
                    <p class="text-sm mt-2 opacity-90">Get the latest news delivered to your inbox.</p>
                    <form action="{{ route('subscribe') }}" method="POST" class="mt-4 space-y-2">
                        @csrf
                        <label for="sidebar_email" class="sr-only">Email</label>
                        <input type="email" name="email" id="sidebar_email" required placeholder="Your email" class="w-full rounded-sm px-3 py-2 text-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--muni-gold)]" style="min-height:44px; border-radius:2px;">
                        <button type="submit" class="w-full bg-white font-bold uppercase text-xs tracking-wider py-2 rounded-sm hover:bg-gray-100" style="color: var(--muni-red); min-height:44px; border-radius:2px;">Subscribe</button>
                    </form>
                </div>

                <!-- Ad / Info Box -->
                <div class="bg-white rounded-sm shadow-sm p-6 border-l-4" style="border-color: var(--muni-blue);">
                    <h4 class="font-bold text-sm" style="color: var(--muni-red-dark);">About Muni University</h4>
                    <p class="text-sm text-gray-600 mt-2">Transforming Lives through quality education, research and innovation in the West Nile region.</p>
                    <a href="#" class="text-sm font-semibold mt-3 inline-block hover:text-[var(--muni-red)]">Learn more <i class="fa-solid fa-arrow-right ms-1"></i></a>
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
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'Muni University',
        'logo' => ['@type' => 'ImageObject', 'url' => asset('assets/images/muni-logo.png')]
    ],
    'datePublished' => $article->published_at?->toIso8601String() ?? $article->created_at->toIso8601String(),
    'dateModified' => $article->updated_at->toIso8601String(),
    'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => url()->current()],
], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
</script>
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => 'Muni University',
    'url' => 'https://news.muni.ac.ug',
    'logo' => asset('assets/images/muni-logo.png'),
    'sameAs' => ['https://www.muni.ac.ug'],
], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
</script>
@endpush
@endsection
