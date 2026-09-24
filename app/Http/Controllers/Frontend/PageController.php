<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\View\View;

class PageController extends Controller
{
    public function show(string $slug): View
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        $meta_title = $page->meta_title ?: $page->title;
        $meta_description = $page->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($page->content), 160);

        return view('frontend.pages.show', compact('page', 'meta_title', 'meta_description'));
    }
}
