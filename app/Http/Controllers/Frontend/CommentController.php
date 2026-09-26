<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // validate the target exists for either articles or newsletters
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'commentable_type' => ['required', 'string', 'in:App\Models\Article,App\Models\Newsletter'],
            'commentable_id' => ['required', 'integer'],
            'author_name' => ['required', 'string', 'max:100'],
            'author_email' => ['required', 'email', 'max:255'],
            'content' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'exists:comments,id'],
        ]);

        $type = $validated['commentable_type'];
        $type::findOrFail($validated['commentable_id']);

        Comment::create([
            'commentable_type' => $type,
            'commentable_id' => $validated['commentable_id'],
            'user_id' => auth()->id(),
            'author_name' => $validated['author_name'],
            'author_email' => $validated['author_email'],
            'content' => $validated['content'],
            'parent_id' => $validated['parent_id'] ?? null,
            'is_approved' => false,
        ]);

        return back()->with('success', 'Your comment has been submitted and is awaiting moderation.');
    }
}