<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Barryvdh\DomPDF\Facade\Pdf;
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
            ->paginate(9)
            ->withQueryString();

        return view('frontend.newsletters.index', compact('newsletters', 'years', 'selectedYear'));
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

    public function downloadPdf(string $slug)
    {
        $newsletter = Newsletter::published()
            ->where('slug', $slug)
            ->firstOrFail();

        // Raw HTML content (TinyMCE body or plain description).
        $htmlContent = $newsletter->content ?: $newsletter->description ?: '';

        // Dompdf cannot fetch images over HTTP(S), so rewrite every storage-hosted
        // image (absolute URLs like https://news.muni.ac.ug/storage/... and relative
        // /storage/... paths) to the local file path under /public/storage. A single
        // pass avoids double-replacing the origin and the /storage/ prefix.
        $siteUrl = preg_replace('#/+\z#', '', url('/'));
        $storageDir = rtrim(str_replace('\\', '/', public_path('storage')), '/');

        $htmlContent = preg_replace_callback(
            '#(src|href|poster|background)\s*=\s*"([^"]+)"#i',
            function (array $m) use ($siteUrl, $storageDir) {
                $url = trim($m[2]);

                // Reject any data URIs / empty srcs.
                if (preg_match('#^data:#i', $url)) {
                    return $m[0];
                }

                // Strip the site origin when it is present.
                if (str_starts_with($url, $siteUrl)) {
                    $url = substr($url, strlen($siteUrl));
                }

                // Drop any cache-busting query/fragment suffixes.
                $url = preg_replace('~[?#].*\z~', '', $url);

                if (preg_match('#^/storage/(.+)$#', $url, $path)) {
                    return $m[1] . '="' . $storageDir . '/' . $path[1] . '"';
                }

                return $m[0];
            },
            $htmlContent
        );

        // Featured image: resolve to its local file path for Dompdf.
        $featuredImagePath = '';
        $featuredImage = $newsletter->image();
        if ($featuredImage) {
            $candidate = public_path('storage/' . $featuredImage);
            if (file_exists($candidate)) {
                $featuredImagePath = str_replace('\\', '/', $candidate);
            }
        }

        $pdf = Pdf::loadView('pdf.newsletter', [
            'newsletter' => $newsletter,
            'processedContent' => $htmlContent,
            'featuredImagePath' => $featuredImagePath,
        ]);
        $pdf->setPaper('A4', 'portrait');

        $filename = 'Muni-Newsletter-' . $newsletter->slug . '.pdf';

        return $pdf->download($filename);
    }
}
