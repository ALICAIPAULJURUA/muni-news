<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $query = Article::with(['category', 'author'])
            ->where('is_published', true)
            ->latest('published_at');

        // Search
        if ($q = $request->input('q')) {
            $query->where(function ($qq) use ($q) {
                $qq->where('title', 'like', "%{$q}%")
                    ->orWhere('summary', 'like', "%{$q}%")
                    ->orWhere('content', 'like', "%{$q}%");
            });
        }

        // Category filter
        if ($categorySlug = $request->input('category')) {
            $query->whereHas('category', fn($qq) => $qq->where('slug', $categorySlug));
        }

        $articles = $query->paginate(9)->withQueryString();
        $categories = Category::orderBy('sort_order')->get();

        return view('frontend.articles.index', compact('articles', 'categories'));
    }

    public function category(string $slug): View
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $articles = Article::with(['category', 'author'])
            ->where('is_published', true)
            ->where('category_id', $category->id)
            ->latest('published_at')
            ->paginate(9);

        $categories = Category::orderBy('sort_order')->get();

        return view('frontend.articles.category', compact('category', 'articles', 'categories'));
    }

    public function show(string $slug): View
    {
        $article = Article::with(['category', 'author', 'tags', 'comments' => fn($q) => $q->where('is_approved', true)->latest()])
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Related articles (same category, exclude current)
        $related = Article::with(['category'])
            ->where('is_published', true)
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        // Breadcrumb and meta
        $meta_title = $article->meta_title ?: $article->title;
        $meta_description = $article->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($article->summary), 160);

        return view('frontend.articles.show', compact('article', 'related', 'meta_title', 'meta_description'));
    }
}
