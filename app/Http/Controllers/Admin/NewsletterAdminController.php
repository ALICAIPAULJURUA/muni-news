<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewsletterRequest;
use App\Mail\NewsletterPublishedMail;
use App\Models\Newsletter;
use App\Models\NewsletterSubscription;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewsletterAdminController extends Controller
{
    public function index(): View
    {
        $newsletters = Newsletter::latest()->paginate(15);
        return view('admin.newsletters.index', compact('newsletters'));
    }
    public function create(): View { return view('admin.newsletters.create'); }
    public function store(NewsletterRequest $request, ImageService $imageService): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['title']);
        $orig = $data['slug']; $i = 1; while (Newsletter::where('slug', $data['slug'])->exists()) $data['slug'] = $orig.'-'.$i++;
        $data['author_id'] = auth()->id();
        $data['publication_year'] = date('Y');
        if ($request->hasFile('featured_image')) $data['featured_image'] = $imageService->storeImage($request->file('featured_image'), 'newsletters');
        $newsletter = Newsletter::create($data);
        if ($request->has('is_published') && $newsletter->is_published) {
            $this->notifySubscribers($newsletter);
        }
        return redirect()->route('admin.newsletters.index')->with('success', 'Newsletter created.');
    }
    public function edit(Newsletter $newsletter): View { return view('admin.newsletters.edit', compact('newsletter')); }
    public function update(NewsletterRequest $request, Newsletter $newsletter, ImageService $imageService): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['title']);
        $orig = $data['slug']; $i = 1; while (Newsletter::where('slug', $data['slug'])->where('id', '!=', $newsletter->id)->exists()) $data['slug'] = $orig.'-'.$i++;
        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $imageService->storeImage($request->file('featured_image'), 'newsletters');
        } else {
            unset($data['featured_image']);
        }
        $data['author_id'] = $newsletter->author_id ?? auth()->id();
        $wasPublished = $newsletter->is_published;
        $newsletter->update($data);
        if (! $wasPublished && $newsletter->is_published) {
            $this->notifySubscribers($newsletter);
        }
        return redirect()->route('admin.newsletters.index')->with('success', 'Newsletter updated.');
    }
    public function destroy(Newsletter $newsletter): RedirectResponse { $newsletter->delete(); return back()->with('success', 'Newsletter deleted.'); }

    /**
     * Email every active subscriber when a newsletter is (newly) published.
     * Each recipient gets a personal, signed unsubscribe link.
     */
    private function notifySubscribers(Newsletter $newsletter): void
    {
        $subscribers = NewsletterSubscription::where('is_active', true)->get();
        if ($subscribers->isEmpty()) {
            return;
        }

        $newsletterUrl = route('newsletters.show', $newsletter->slug);

        try {
            foreach ($subscribers as $subscriber) {
                $unsubscribeUrl = URL::signedRoute('unsubscribe.show', ['email' => $subscriber->email]);
                Mail::to($subscriber->email)->send(new NewsletterPublishedMail($newsletter, $newsletterUrl, $unsubscribeUrl));
            }
        } catch (\Exception $e) {
            Log::error('Newsletter notification emails failed for "'.$newsletter->title.'": '.$e->getMessage());
        }
    }
}