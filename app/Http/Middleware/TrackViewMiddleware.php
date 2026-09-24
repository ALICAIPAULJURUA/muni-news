<?php

namespace App\Http\Middleware;

use App\Models\Article;
use App\Models\ArticleView;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackViewMiddleware
{
    /**
     * Handle an incoming request for device-based view counting.
     * Fingerprint = sha256(IP + User-Agent)
     * Skip if same fingerprint viewed within 24h.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track successful GET responses
        if (! $request->isMethod('get') || $response->getStatusCode() !== 200) {
            return $response;
        }

        // Try to resolve article from route parameter
        $slug = $request->route('slug');
        if (! $slug) {
            $slug = $request->route('article');
        }

        if (! $slug) {
            return $response;
        }

        // Find article (handle both slug string and model)
        if (is_string($slug)) {
            $article = Article::where('slug', $slug)->first();
        } elseif ($slug instanceof Article) {
            $article = $slug;
        } else {
            $article = null;
        }

        if (! $article) {
            return $response;
        }

        // Only count published articles
        if (! $article->is_published) {
            return $response;
        }

        $ip = $request->ip() ?? '0.0.0.0';
        $userAgent = $request->userAgent() ?? '';
        $fingerprint = hash('sha256', $ip . $userAgent);

        $exists = ArticleView::where('article_id', $article->id)
            ->where('device_fingerprint', $fingerprint)
            ->where('viewed_at', '>', now()->subDay())
            ->exists();

        if ($exists) {
            return $response;
        }

        // Increment views and log
        $article->increment('views');

        ArticleView::create([
            'article_id' => $article->id,
            'device_fingerprint' => $fingerprint,
            'ip_address' => $ip,
            'viewed_at' => now(),
        ]);

        return $response;
    }
}
