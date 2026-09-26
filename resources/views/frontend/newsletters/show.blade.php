@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6" style="max-width:800px;">
    {{-- Breadcrumb --}}
    <nav aria-label="Breadcrumb" class="text-sm text-gray-500 mb-4">
        <a href="{{ url('/') }}" class="hover:text-[var(--muni-red)]">Home</a> <span class="mx-2">/</span>
        <a href="{{ route('newsletters.index') }}" class="hover:text-[var(--muni-red)]">Newsletters</a> <span class="mx-2">/</span>
        <span class="truncate" style="color: var(--muni-red-dark);">{{ \Illuminate\Support\Str::limit($newsletter->title, 50) }}</span>
    </nav>

    {{-- Publication header --}}
    <header class="text-center mb-8">
        <span class="badge-muni inline-block mb-3"><i class="fa-solid fa-file-invoice me-1"></i> University Newsletter</span>
        <h1 class="text-3xl lg:text-4xl font-black leading-tight mb-3" style="font-family:var(--font-heading); color: var(--muni-red-dark);">{{ $newsletter->title }}</h1>
        <div class="flex flex-wrap items-center justify-center gap-3 text-sm text-gray-500">
            <span><i class="fa-solid fa-user me-1"></i>{{ $newsletter->author->full_name ?? $newsletter->author->username ?? 'Muni University' }}</span>
            <span>•</span>
            <span><i class="fa-regular fa-calendar me-1"></i>{{ optional($newsletter->created_at)->format('F j, Y') ?? $newsletter->publication_year }}</span>
            <span>•</span>
            <span><i class="fa-solid fa-download me-1"></i>{{ $newsletter->download_count }} downloads</span>
        </div>
    </header>

    {{-- Featured image --}}
    @if($newsletter->image())
    <figure class="mb-6">
        <img loading="lazy" src="{{ asset('storage/' . $newsletter->image()) }}" alt="{{ $newsletter->title }}" class="w-full rounded-sm shadow-md" style="max-height:480px; object-fit:cover;">
    </figure>
    @endif

    {{-- PDF download --}}
    @if($newsletter->file_path)
    <div class="mb-6 text-center">
        <a href="{{ route('newsletters.download', $newsletter) }}" class="inline-flex items-center gap-2 btn-muni">
            <i class="fa-solid fa-file-pdf"></i> Download PDF Version
        </a>
    </div>
    @endif

    {{-- Body content (TinyMCE or fallback description) --}}
    <article class="bg-white rounded-sm shadow-sm border border-gray-200 p-6 lg:p-8 mb-8" style="border-top: 4px solid var(--muni-gold);">
        @if($newsletter->content)
            <div class="prose max-w-none article-content mx-auto" style="line-height:1.8;">
                {!! $newsletter->content !!}
            </div>
        @else
            <div class="prose max-w-none" style="line-height:1.8;">
                <p>{{ $newsletter->description }}</p>
            </div>
        @endif
    </article>

    {{-- Likes + Sharing --}}
    <section class="bg-white rounded-sm shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex flex-wrap items-center justify-between gap-4">
            @include('frontend.partials.like-button', ['likable' => $newsletter])
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider mb-2" style="color: var(--muni-red-dark);">Share this newsletter</h3>
                @include('frontend.partials.share-buttons', [
                    'shareUrl' => url()->current(),
                    'shareTitle' => $newsletter->title,
                ])
            </div>
        </div>
    </section>

    {{-- Comments --}}
    <section class="bg-white rounded-sm shadow-sm border border-gray-200 p-6 mb-8" id="comments">
        <h3 class="text-xl font-bold mb-4" style="font-family:var(--font-heading); color: var(--muni-red-dark);">
            <i class="fa-solid fa-comments me-2" style="color: var(--muni-red);"></i> Comments ({{ $newsletter->comments->count() }})
        </h3>

        @forelse($newsletter->comments->where('parent_id', null) as $comment)
            <div class="bg-white rounded-sm border p-4 mb-4" style="border-left: 3px solid var(--muni-gold);">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold" style="background: var(--muni-red);">{{ strtoupper(substr($comment->author_name,0,1)) }}</div>
                    <div>
                        <p class="text-sm font-bold" style="color: var(--muni-red-dark);">{{ $comment->author_name }}</p>
                        <p class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <p class="text-sm text-gray-700" style="line-height:1.6;">{{ $comment->content }}</p>

                @php $replies = $newsletter->comments->where('parent_id', $comment->id); @endphp
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

        <div class="bg-gray-50 rounded-sm border p-6" id="comment-form">
            <h4 class="font-bold mb-3" style="color: var(--muni-red-dark);">Leave a Comment</h4>
            <form method="POST" action="{{ route('comments.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="commentable_type" value="{{ get_class($newsletter) }}">
                <input type="hidden" name="commentable_id" value="{{ $newsletter->id }}">
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
                    <textarea name="content" id="content-input" rows="4" required class="w-full border-2 rounded-sm px-3 py-2 text-sm" style="border-radius:2px;" placeholder="Your thoughts...">{{ old('content') }}</textarea>
                    @error('content')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="btn-muni text-sm">Post Comment</button>
                <p class="text-xs text-gray-500">Your comment will be visible after moderation.</p>
            </form>
        </div>
    </section>

    {{-- Related newsletters --}}
    @if($related->count())
    <section>
        <h3 class="font-bold mb-4" style="font-family:var(--font-heading); color: var(--muni-red-dark);">More Newsletters</h3>
        <div class="grid md:grid-cols-3 gap-4">
            @foreach($related as $rel)
            <a href="{{ route('newsletters.show', $rel->slug) }}" class="card-muni p-4">
                @if($rel->image())
                    <img loading="lazy" src="{{ asset('storage/' . $rel->image()) }}" alt="" class="w-full h-24 object-cover rounded-sm mb-2">
                @endif
                <p class="text-xs text-gray-500">{{ $rel->publication_year }}</p>
                <h4 class="font-semibold text-sm mt-1 hover:text-[var(--muni-red)]">{{ $rel->title }}</h4>
            </a>
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection

@push('scripts')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'NewsArticle',
    'headline' => $newsletter->title,
    'description' => $meta_description,
    'image' => $og_image,
    'author' => ['@type' => 'Person', 'name' => $newsletter->author->full_name ?? $newsletter->author->username ?? 'Muni University'],
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'Muni University',
        'logo' => ['@type' => 'ImageObject', 'url' => asset('assets/images/muni-logo.png')]
    ],
    'datePublished' => $newsletter->created_at?->toIso8601String(),
    'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => url()->current()],
], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
</script>
@endpush