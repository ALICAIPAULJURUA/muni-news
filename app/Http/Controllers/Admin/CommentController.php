<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->input('status', 'pending');
        $query = Comment::with(['article','user'])->latest();
        if($status==='pending') $query->where('is_approved', false);
        elseif($status==='approved') $query->where('is_approved', true);

        $comments = $query->paginate(20)->withQueryString();
        return view('admin.comments.index', compact('comments','status'));
    }

    public function approve(Comment $comment): RedirectResponse
    {
        $comment->update(['is_approved'=>true]);
        return back()->with('success','Comment approved.');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();
        return back()->with('success','Comment deleted.');
    }
}
