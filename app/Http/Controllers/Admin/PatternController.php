<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SectionPatternRequest;
use App\Models\SectionPattern;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PatternController extends Controller
{
    public const SECTION_OPTIONS = [
        'newsletter_cta' => 'Newsletter CTA Section',
        'hero' => 'Hero Section',
        'footer' => 'Footer',
        'about_header' => 'About Page Header',
    ];

    public function index(): View
    {
        $patterns = SectionPattern::latest()->get();
        return view('admin.patterns.index', compact('patterns'));
    }

    public function create(): View
    {
        return view('admin.patterns.create', ['sectionOptions' => self::SECTION_OPTIONS]);
    }

    public function store(SectionPatternRequest $request, ImageService $imageService): RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image_path'] = $imageService->storeImage($request->file('image'), 'patterns', 1600);
        }
        $data['is_active'] = $request->boolean('is_active');
        SectionPattern::create($data);
        return redirect()->route('admin.patterns.index')->with('success', 'Section pattern saved.');
    }

    public function edit(SectionPattern $pattern): View
    {
        return view('admin.patterns.edit', ['pattern' => $pattern, 'sectionOptions' => self::SECTION_OPTIONS]);
    }

    public function update(SectionPatternRequest $request, SectionPattern $pattern, ImageService $imageService): RedirectResponse
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image_path'] = $imageService->storeImage($request->file('image'), 'patterns', 1600);
        }
        $data['is_active'] = $request->boolean('is_active');
        $pattern->update($data);
        return redirect()->route('admin.patterns.index')->with('success', 'Section pattern updated.');
    }

    public function destroy(SectionPattern $pattern): RedirectResponse
    {
        if ($pattern->image_path) {
            Storage::disk('public')->delete($pattern->image_path);
        }
        $pattern->delete();
        return back()->with('success', 'Section pattern deleted.');
    }
}