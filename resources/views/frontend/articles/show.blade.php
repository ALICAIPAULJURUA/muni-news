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

                <!-- Comments Section -->
                <section class="mt-10 pt-6 border-t border-gray-200" id="comments">
                    <h3 class="text-xl font-bold mb-4" style="font-family:var(--font-heading); color: var(--muni-red-dark);">
                        <i class="fa-solid fa-comments me-2" style="color: var(--muni-red);"></i> Comments ({{ $article->comments->count() }})
                    </h3>

                    @forelse($article->comments->where('parent_id', null) as $comment)
                        <div class="bg-white rounded-sm border p-4 mb-4" style="border-left: 3px solid var(--muni-gold);">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold" style="background: var(--muni-red);">{{ strtoupper(substr($comment->author_name,0,1)) }}</div>
                                <div>
                                    <p class="text-sm font-bold" style="color: var(--muni-red-dark);">{{ $comment->author_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-700" style="line-height:1.6;">{{ $comment->content }}</p>

                            @php $replies = $article->comments->where('parent_id', $comment->id); @endphp
                            @if($replies->count())
                                <div class="mt-4 space-y-3 ps-4 border-l-2" style="border-color: var(--color-border);">
                                    @foreach($replies as $reply)
                                        <div class="bg-gray-50 rounded-sm p-3">
                                            <div class="flex items-center gap-2 mb-1">
                                                <div class="w-6 h-6 rounded-full flex items-center justify-center text-white text-xs font-bold" style="background: var(--muni-blue);">{{ strtoupper(substr($reply->author_name,0,1)) }}</div>
                                                <p class="text-xs font-bold">{{ $reply->author_name }}</p>
                                                <span class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-sm text-gray-700">{{ $reply->content }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <button onclick="document.getElementById('parent_id').value='{{ $comment->id }}'; document.getElementById('comment-form').scrollIntoView({behavior:'smooth'}); document.getElementById('content-input').focus();" class="mt-3 text-xs font-bold uppercase tracking-wider hover:text-[var(--muni-red)]" style="color: var(--muni-blue);">Reply</button>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 mb-6">No comments yet. Be the first to share your thoughts!</p>
                    @endforelse

                    <div class="bg-white rounded-sm shadow-sm border p-6" id="comment-form">
                        <h4 class="font-bold mb-3" style="color: var(--muni-red-dark);">Leave a Comment</h4>
                        <form method="POST" action="{{ route('comments.store') }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="article_id" value="{{ $article->id }}">
                            <input type="hidden" name="parent_id" id="parent_id" value="">
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-bold">Name *</label>
                                    <input type="text" name="author_name" value="{{ old('author_name', auth()->user()->full_name ?? '') }}" required class="w-full border-2 rounded-sm px-3 py-2 text-sm" style="min-height:44px; border-radius:2px;" placeholder="Your name">
                                    @error('author_name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="text-sm font-bold">Email *</label>
                                    <input type="email" name="author_email" value="{{ old('author_email', auth()->user()->email ?? '') }}" required class="w-full border-2 rounded-sm px-3 py-2 text-sm" style="min-height:44px; border-radius:2px;" placeholder="Your email">
                                    @error('author_email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                                </div>
                            </div>
                            <div>
                                <label class="text-sm font-bold">Comment *</label>
                                <textarea name="content" rows="4" required class="w-full border-2 rounded-sm px-3 py-2 text-sm" style="border-radius:2px;" placeholder="Your thoughts...">{{ old('content') }}</textarea>
                                @error('content')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <button type="submit" class="btn-muni text-sm">Post Comment</button>
                            <p class="text-xs text-gray-500">Your comment will be visible after moderation.</p>
                        </form>
                    </div>
                </section>
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
