<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\View\View;

class MediaController extends Controller
{
    public function index(): View
    {
        $media = Media::with('uploader')
            ->latest()
            ->paginate(18);

        return view('frontend.media.index', compact('media'));
    }
}
