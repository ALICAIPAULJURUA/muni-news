@extends('layouts.admin')
@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-xl font-bold" style="font-family:var(--font-heading); color: var(--muni-red-dark);">Articles</h2>
    <a href="{{ route('admin.articles.create') }}" class="btn-muni inline-flex items-center gap-2 text-sm"><i class="fa-solid fa-plus"></i> New Article</a>
</div>

<form method="GET" class="bg-white rounded-sm shadow-sm p-4 mb-4 flex flex-wrap gap-3 items-end border">
    <div>
        <label class="text-xs font-bold uppercase">Search</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Title..." class="border-2 rounded-sm px-3 py-2 text-sm" style="min-height:44px;">
    </div>
    <div>
        <label class="text-xs font-bold uppercase">Category</label>
        <select name="category" class="border-2 rounded-sm px-3 py-2 text-sm bg-white" style="min-height:44px;">
            <option value="">All</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category')==$cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="text-xs font-bold uppercase">Status</label>
        <select name="status" class="border-2 rounded-sm px-3 py-2 text-sm bg-white" style="min-height:44px;">
            <option value="">All</option>
            <option value="published" {{ request('status')=='published' ? 'selected' : '' }}>Published</option>
            <option value="draft" {{ request('status')=='draft' ? 'selected' : '' }}>Draft</option>
            <option value="featured" {{ request('status')=='featured' ? 'selected' : '' }}>Featured</option>
            <option value="breaking" {{ request('status')=='breaking' ? 'selected' : '' }}>Breaking</option>
        </select>
    </div>
    <button type="submit" class="btn-muni text-sm">Filter</button>
    <a href="{{ route('admin.articles.index') }}" class="text-sm underline">Clear</a>
</form>

<div class="bg-white rounded-sm shadow-sm border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-white text-xs uppercase tracking-wider" style="background: var(--muni-red-dark);">
                    <th class="px-4 py-3 text-left" style="border-bottom:3px solid var(--muni-gold);">Title</th>
                    <th class="px-4 py-3 text-left" style="border-bottom:3px solid var(--muni-gold);">Category</th>
                    <th class="px-4 py-3 text-left" style="border-bottom:3px solid var(--muni-gold);">Author</th>
                    <th class="px-4 py-3 text-left" style="border-bottom:3px solid var(--muni-gold);">Status</th>
                    <th class="px-4 py-3 text-left" style="border-bottom:3px solid var(--muni-gold);">Views</th>
                    <th class="px-4 py-3 text-right" style="border-bottom:3px solid var(--muni-gold);">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($articles as $article)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="font-semibold line-clamp-1" style="color: var(--muni-red-dark);">{{ $article->title }}</div>
                        <div class="text-xs text-gray-500">{{ Str::limit($article->slug,30) }} • {{ $article->created_at->format('M d, Y') }}</div>
                    </td>
                    <td class="px-4 py-3"><span class="badge-muni text-xs">{{ $article->category->name ?? '-' }}</span></td>
                    <td class="px-4 py-3 text-xs">{{ $article->author->full_name ?? $article->author->username ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if($article->is_published)<span class="px-2 py-1 rounded-sm text-xs font-bold" style="background: var(--muni-blue); color:#fff;">Published</span> @else <span class="px-2 py-1 rounded-sm text-xs font-bold bg-gray-400 text-white">Draft</span> @endif
                        @if($article->is_featured)<span class="px-2 py-1 rounded-sm text-xs font-bold ms-1" style="background: var(--muni-gold); color: var(--muni-red-dark);">Featured</span>@endif
                        @if($article->is_breaking)<span class="px-2 py-1 rounded-sm text-xs font-bold ms-1" style="background: var(--muni-red); color:#fff;">Breaking</span>@endif
                    </td>
                    <td class="px-4 py-3">{{ $article->views }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('article.show', $article->slug) }}" target="_blank" class="text-xs px-2 py-1 rounded-sm border hover:bg-gray-50">View</a>
                        <a href="{{ route('admin.articles.edit', $article) }}" class="text-xs px-2 py-1 rounded-sm ms-1" style="background: var(--muni-blue); color:#fff;">Edit</a>
                        <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" class="inline" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="text-xs px-2 py-1 rounded-sm ms-1 bg-red-600 text-white">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-12 text-center text-gray-500">No articles found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t">{{ $articles->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
