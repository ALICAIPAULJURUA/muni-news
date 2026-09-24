<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Download;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadController extends Controller
{
    public function index(): View
    {
        $selectedCategory = request('category');

        $categories = Download::select('category')->distinct()->pluck('category');

        $downloads = Download::when($selectedCategory, fn($q) => $q->where('category', $selectedCategory))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('frontend.downloads.index', compact('downloads', 'categories', 'selectedCategory'));
    }

    public function download(Download $download): BinaryFileResponse
    {
        $download->increment('download_count');
        $path = storage_path('app/public/' . ltrim($download->file_path, '/'));
        if (! file_exists($path)) {
            abort(404);
        }
        return response()->download($path);
    }
}
