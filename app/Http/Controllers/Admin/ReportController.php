<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\ArticleView;
use App\Models\Comment;
use App\Models\NewsletterSubscription;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(): View
    {
        $stats = [
            'articles' => Article::count(),
            'published' => Article::where('is_published', true)->count(),
            'drafts' => Article::where('is_published', false)->count(),
            'views' => Article::sum('views'),
            'comments' => Comment::count(),
            'pending_comments' => Comment::where('is_approved', false)->count(),
            'subscribers' => NewsletterSubscription::count(),
        ];

        $topArticles = Article::orderByDesc('views')->take(5)->get(['title','slug','views']);
        $recentViews = ArticleView::with('article')->latest('viewed_at')->take(10)->get();

        $viewsByDay = ArticleView::select(DB::raw("DATE(viewed_at) as date"), DB::raw("COUNT(*) as count"))
            ->groupBy(DB::raw("DATE(viewed_at)"))
            ->orderBy('date','desc')
            ->take(7)
            ->get();

        $categoryStats = DB::table('categories')
            ->leftJoin('articles','categories.id','=','articles.category_id')
            ->select('categories.name', DB::raw('COUNT(articles.id) as count'))
            ->groupBy('categories.id','categories.name')
            ->get();

        return view('admin.reports.index', compact('stats','topArticles','recentViews','viewsByDay','categoryStats'));
    }
}
