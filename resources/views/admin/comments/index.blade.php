@extends('layouts.admin')
@section('content')
<h2 class="text-xl font-bold mb-6" style="font-family:var(--font-heading); color: var(--muni-red-dark);">Comments</h2>

<div class="flex gap-2 mb-4">
    <a href="{{ route('admin.comments.index', ['status'=>'pending']) }}" class="px-4 py-2 rounded-sm text-sm font-bold border-2 {{ $status=='pending' ? 'text-white' : '' }}" style="{{ $status=='pending' ? 'background: var(--muni-red); border-color: var(--muni-red);' : 'border-color: var(--color-border);' }}">Pending ({{ \App\Models\Comment::where('is_approved',false)->count() }})</a>
    <a href="{{ route('admin.comments.index', ['status'=>'approved']) }}" class="px-4 py-2 rounded-sm text-sm font-bold border-2 {{ $status=='approved' ? 'text-white' : '' }}" style="{{ $status=='approved' ? 'background: var(--muni-red); border-color: var(--muni-red);' : 'border-color: var(--color-border);' }}">Approved</a>
</div>

<div class="bg-white rounded-sm shadow-sm border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-white text-xs uppercase" style="background: var(--muni-red-dark);">
                    <th class="px-4 py-3 text-left" style="border-bottom:3px solid var(--muni-gold);">Article</th>
                    <th class="px-4 py-3 text-left" style="border-bottom:3px solid var(--muni-gold);">Author</th>
                    <th class="px-4 py-3 text-left" style="border-bottom:3px solid var(--muni-gold);">Content</th>
                    <th class="px-4 py-3 text-right" style="border-bottom:3px solid var(--muni-gold);">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($comments as $comment)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        @if($comment->commentable)
                            @if(method_exists($comment->commentable, 'slug'))
                                <a href="{{ route($comment->commentable instanceof \App\Models\Article ? 'article.show' : 'newsletters.show', $comment->commentable->slug) }}" target="_blank" class="font-semibold hover:text-[var(--muni-red)]" style="color: var(--muni-red-dark);">{{ Str::limit($comment->commentable->title,30) }}</a>
                            @else
                                <span class="font-semibold" style="color: var(--muni-red-dark);">{{ Str::limit($comment->commentable->title,30) }}</span>
                            @endif
                            <div class="text-xs text-gray-500 mt-1">
                                <span class="uppercase">{{ class_basename($comment->commentable_type) }}</span>
                                @if($comment->commentable instanceof \App\Models\Newsletter && $comment->commentable->is_published)
                                    <a href="{{ route('newsletters.show', $comment->commentable->slug) }}" class="text-xs hover:text-[var(--muni-red)]">View newsletter</a>
                                @endif
                            </div>
                        @else
                            <span class="text-xs text-gray-400">Content deleted</span>
                        @endif
                        <div class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-semibold text-xs">{{ $comment->author_name }}</div>
                        <div class="text-xs text-gray-500">{{ $comment->author_email }}</div>
                        @if($comment->user)<div class="text-xs" style="color: var(--muni-blue);">User: {{ $comment->user->username }}</div>@endif
                    </td>
                    <td class="px-4 py-3"><div class="text-sm line-clamp-3 max-w-xs">{{ $comment->content }}</div></td>
                    <td class="px-4 py-3 text-right">
                        @if(!$comment->is_approved)
                            <form method="POST" action="{{ route('admin.comments.approve', $comment) }}" class="inline">@csrf @method('PATCH')<button class="text-xs px-3 py-1 rounded-sm" style="background: var(--muni-blue); color:#fff;">Approve</button></form>
                        @else
                            <span class="text-xs px-2 py-1 rounded-sm bg-green-600 text-white">Approved</span>
                        @endif
                        <form method="POST" action="{{ route('admin.comments.destroy', $comment) }}" class="inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="text-xs px-2 py-1 rounded-sm bg-red-600 text-white ms-1">Delete</button></form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-12 text-center text-gray-500">No comments.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4 border-t">{{ $comments->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
