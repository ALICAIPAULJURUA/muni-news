<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use App\Models\NewsletterSubscription;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'articles' => Article::count(),
            'views' => Article::sum('views'),
            'comments' => Comment::count(),
            'subscribers' => NewsletterSubscription::count(),
        ];

        $recentArticles = Article::with(['category', 'author'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentArticles'));
    }
}
