<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class NewsletterController extends Controller
{
    public function index(Request $request): View
    {
        $selectedYear = $request->input('year');

        $years = Newsletter::selectRaw('DISTINCT publication_year as year')
            ->published()
            ->orderByDesc('publication_year')
            ->pluck('year');

        $newsletters = Newsletter::published()
            ->when($selectedYear, fn($q) => $q->where('publication_year', $selectedYear))
            ->orderByDesc('publication_year')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // For 2-column layout: articles left, newsletters right
        $articles = Article::with(['category'])
            ->where('is_published', true)
            ->latest('published_at')
            ->take(6)
            ->get();

        return view('frontend.newsletters.index', compact('newsletters', 'years', 'selectedYear', 'articles'));
    }

    public function show(string $slug): View
    {
        $newsletter = Newsletter::with(['comments', 'likes'])
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $meta_title = $newsletter->title;
        $meta_description = \Illuminate\Support\Str::limit(strip_tags($newsletter->description ?: ''), 160);
        $og_image = $newsletter->image()
            ? asset('storage/' . $newsletter->image())
            : asset('assets/images/muni-logo.png');

        $related = Newsletter::published()
            ->where('id', '!=', $newsletter->id)
            ->latest()
            ->take(3)
            ->get();

        return view('frontend.newsletters.show', compact('newsletter', 'related', 'meta_title', 'meta_description', 'og_image'));
    }

    public function download(Newsletter $newsletter): BinaryFileResponse
    {
        $newsletter->increment('download_count');
        $path = storage_path('app/public/' . ltrim($newsletter->file_path, '/'));
        if (! file_exists($path)) {
            abort(404);
        }
        return response()->download($path);
    }
}
