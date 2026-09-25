<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $featuredArticle = Article::with(['category', 'author'])
            ->where('is_published', true)
            ->where('is_featured', true)
            ->latest('published_at')
            ->first();

        $secondaryArticles = Article::with(['category', 'author'])
            ->where('is_published', true)
            ->where('id', '!=', $featuredArticle?->id)
            ->where('is_featured', true)
            ->latest('published_at')
            ->take(2)
            ->get();

        // Fallback if not enough featured
        if ($secondaryArticles->count() < 2) {
            $needed = 2 - $secondaryArticles->count();
            $fallback = Article::with(['category', 'author'])
                ->where('is_published', true)
                ->whereNotIn('id', collect([$featuredArticle?->id])->merge($secondaryArticles->pluck('id'))->filter()->toArray())
                ->latest('published_at')
                ->take($needed)
                ->get();
            $secondaryArticles = $secondaryArticles->merge($fallback);
        }

        $latestNews = Article::with(['category', 'author'])
            ->where('is_published', true)
            ->latest('published_at')
            ->take(6)
            ->get();

        $breakingArticles = Article::where('is_breaking', true)
            ->where('is_published', true)
            ->latest('published_at')
            ->take(5)
            ->get();

        // Category highlights: each category with 4 latest (exclude events, which has its own page)
        $categories = Category::with(['articles' => function ($q) {
            $q->where('is_published', true)->latest('published_at')->take(4);
        }])
            ->whereNotIn('slug', ['news', 'events'])
            ->orderBy('sort_order')
            ->get();

        return view('frontend.home', compact(
            'featuredArticle',
            'secondaryArticles',
            'latestNews',
            'breakingArticles',
            'categories'
        ));
    }
}
