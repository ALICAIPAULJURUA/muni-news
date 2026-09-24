@extends('layouts.admin')

@section('header', 'Dashboard')
@section('content')
<div class="space-y-6">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-sm shadow-sm p-5 border-t-4" style="border-top-color: var(--muni-red);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Articles</p>
                    <p class="text-2xl font-bold mt-1" style="color: var(--muni-red-dark);">{{ $stats['articles'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-1">Total published</p>
                </div>
                <div class="w-12 h-12 rounded-sm flex items-center justify-center" style="background: var(--muni-red);">
                    <i class="fa-solid fa-newspaper text-white text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-sm shadow-sm p-5 border-t-4" style="border-top-color: var(--muni-blue);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Views</p>
                    <p class="text-2xl font-bold mt-1" style="color: var(--muni-red-dark);">{{ number_format($stats['views'] ?? 0) }}</p>
                    <p class="text-xs text-gray-400 mt-1">Total article views</p>
                </div>
                <div class="w-12 h-12 rounded-sm flex items-center justify-center" style="background: var(--muni-blue);">
                    <i class="fa-solid fa-eye text-white text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-sm shadow-sm p-5 border-t-4" style="border-top-color: var(--muni-gold);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Comments</p>
                    <p class="text-2xl font-bold mt-1" style="color: var(--muni-red-dark);">{{ $stats['comments'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-1">Pending & approved</p>
                </div>
                <div class="w-12 h-12 rounded-sm flex items-center justify-center" style="background: var(--muni-gold); color: var(--muni-red-dark);">
                    <i class="fa-solid fa-comments text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-sm shadow-sm p-5 border-t-4" style="border-top-color: var(--muni-emerald, #00b9f1);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-500">Subscribers</p>
                    <p class="text-2xl font-bold mt-1" style="color: var(--muni-red-dark);">{{ $stats['subscribers'] ?? 0 }}</p>
                    <p class="text-xs text-gray-400 mt-1">Newsletter subs</p>
                </div>
                <div class="w-12 h-12 rounded-sm flex items-center justify-center" style="background: #00b9f1;">
                    <i class="fa-solid fa-envelope text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-sm shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between" style="border-bottom: 2px solid var(--muni-gold); background: var(--muni-red-dark);">
                <h3 class="font-semibold text-white" style="font-family:'Merriweather',serif;">Recent Articles</h3>
                <a href="#" class="text-xs text-white/80 hover:text-white underline">View all</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentArticles ?? [] as $article)
                    <div class="px-6 py-4 flex items-start gap-4 hover:bg-gray-50">
                        <div class="w-16 h-12 bg-gray-200 rounded-sm flex-shrink-0 overflow-hidden">
                            @if($article->featured_image)
                                <img src="{{ asset('storage/' . $article->featured_image) }}" alt="" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center" style="background: var(--color-bg-tertiary);"><i class="fa-solid fa-image text-gray-400"></i></div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold truncate" style="color: var(--muni-red-dark);">{{ $article->title }}</p>
                            <p class="text-xs text-gray-500">{{ $article->category->name ?? 'Uncategorized' }} • {{ $article->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-sm font-semibold uppercase" style="background: {{ $article->is_published ? 'var(--muni-blue)' : '#718096' }}; color: #fff;">{{ $article->is_published ? 'Published' : 'Draft' }}</span>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-gray-500">
                        <i class="fa-solid fa-inbox text-3xl mb-2"></i>
                        <p class="text-sm">No articles yet. Create your first article.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-sm shadow-sm p-6">
            <h3 class="font-semibold mb-4" style="font-family:'Merriweather',serif; color: var(--muni-red);">Quick Actions</h3>
            <div class="space-y-3">
                <a href="#" class="flex items-center gap-3 w-full px-4 py-3 rounded-sm border-2 font-semibold text-sm transition" style="border-color: var(--muni-red); color: var(--muni-red);">
                    <i class="fa-solid fa-plus"></i> New Article
                </a>
                <a href="#" class="flex items-center gap-3 w-full px-4 py-3 rounded-sm bg-gray-50 border border-gray-200 hover:bg-gray-100 text-sm">
                    <i class="fa-solid fa-layer-group" style="color: var(--muni-blue);"></i> Manage Categories
                </a>
                <a href="#" class="flex items-center gap-3 w-full px-4 py-3 rounded-sm bg-gray-50 border border-gray-200 hover:bg-gray-100 text-sm">
                    <i class="fa-solid fa-photo-film" style="color: var(--muni-gold);"></i> Media Library
                </a>
                <a href="#" class="flex items-center gap-3 w-full px-4 py-3 rounded-sm bg-gray-50 border border-gray-200 hover:bg-gray-100 text-sm">
                    <i class="fa-solid fa-users" style="color: var(--muni-red);"></i> Manage Users
                </a>
            </div>

            <div class="mt-6 p-4 rounded-sm" style="background: #f7f9fc; border-left: 4px solid var(--muni-gold);">
                <p class="text-xs font-semibold uppercase tracking-wider" style="color: var(--muni-red-dark);">Tip</p>
                <p class="text-sm text-gray-600 mt-1">Use <span class="font-semibold">TinyMCE</span> to create rich articles with images and embeds. Remember to set featured images for better SEO.</p>
            </div>
        </div>
    </div>
</div>
@endsection
