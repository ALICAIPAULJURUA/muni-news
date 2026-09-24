<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreArticleRequest;
use App\Http\Requests\Admin\UpdateArticleRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(Request $request): View
    {
        $query = Article::with(['category','author'])->latest();

        if ($q = $request->input('search')) {
            $query->where(function($qq) use($q){
                $qq->where('title','like',"%{$q}%")->orWhere('summary','like',"%{$q}%");
            });
        }
        if ($cat = $request->input('category')) {
            $query->where('category_id', $cat);
        }
        if ($status = $request->input('status')) {
            if ($status==='published') $query->where('is_published', true);
            elseif ($status==='draft') $query->where('is_published', false);
            elseif ($status==='featured') $query->where('is_featured', true);
            elseif ($status==='breaking') $query->where('is_breaking', true);
        }

        $articles = $query->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.articles.index', compact('articles','categories'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        return view('admin.articles.create', compact('categories','tags'));
    }

    public function store(StoreArticleRequest $request, ImageService $imageService): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['title']);
        // Ensure unique slug
        $original = $data['slug'];
        $i=1;
        while(Article::where('slug',$data['slug'])->exists()){ $data['slug']=$original.'-'.$i++; }

        $data['author_id'] = auth()->id();
        $data['published_at'] = $data['is_published'] ? ($data['published_at'] ?? now()) : null;

        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            if (! $imageService->validateImage($file)) {
                return back()->withErrors(['featured_image'=>'Invalid image type or size >5MB'])->withInput();
            }
            $data['featured_image'] = $imageService->storeImage($file, 'articles');
        }

        $tags = $data['tags'] ?? [];
        unset($data['tags']);

        $article = Article::create($data);

        // Sync tags
        $tagIds = [];
        foreach($tags as $tagName){
            $tag = Tag::firstOrCreate(['slug'=>Str::slug($tagName)], ['name'=>trim($tagName)]);
            $tagIds[] = $tag->id;
        }
        if($tagIds) $article->tags()->sync($tagIds);

        return redirect()->route('admin.articles.index')->with('success','Article created successfully.');
    }

    public function edit(Article $article): View
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        return view('admin.articles.edit', compact('article','categories','tags'));
    }

    public function update(UpdateArticleRequest $request, Article $article, ImageService $imageService): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['title']);
        $original = $data['slug'];
        $i=1;
        while(Article::where('slug',$data['slug'])->where('id','!=',$article->id)->exists()){ $data['slug']=$original.'-'.$i++; }

        $data['published_at'] = $data['is_published'] ? ($data['published_at'] ?? $article->published_at ?? now()) : null;

        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            if (! $imageService->validateImage($file)) {
                return back()->withErrors(['featured_image'=>'Invalid image type or size >5MB'])->withInput();
            }
            $data['featured_image'] = $imageService->storeImage($file, 'articles');
        } else {
            unset($data['featured_image']);
        }

        $tags = $data['tags'] ?? [];
        unset($data['tags']);

        $article->update($data);

        $tagIds = [];
        foreach($tags as $tagName){
            $tag = Tag::firstOrCreate(['slug'=>Str::slug($tagName)], ['name'=>trim($tagName)]);
            $tagIds[] = $tag->id;
        }
        $article->tags()->sync($tagIds);

        return redirect()->route('admin.articles.index')->with('success','Article updated successfully.');
    }

    public function preview(Article $article): View
    {
        $article->load(['category','author','tags','comments' => fn($q) => $q->where('is_approved', true)->latest()]);
        $related = Article::with(['category'])
            ->where('is_published', true)
            ->where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        $meta_title = $article->meta_title ?: $article->title;
        $meta_description = $article->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($article->summary), 160);
        $og_image = $article->featured_image ? asset('storage/' . $article->featured_image) : asset('assets/images/muni-logo.png');

        return view('admin.articles.preview', compact('article','related','meta_title','meta_description','og_image'));
    }

    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();
        return back()->with('success','Article moved to trash.');
    }

    public function uploadImage(Request $request, ImageService $imageService): JsonResponse
    {
        $request->validate([
            'file' => ['required','image','mimes:jpeg,png,jpg,gif,webp,svg','max:5120'],
        ]);

        $file = $request->file('file');
        // Validate real mime
        $allowed = ['image/jpeg','image/png','image/gif','image/webp','image/svg+xml'];
        if (! in_array($file->getMimeType(), $allowed)) {
            return response()->json(['error'=>'Invalid MIME type'], 422);
        }

        $path = $imageService->storeImage($file, 'articles');
        $location = asset('storage/' . $path);

        return response()->json(['location' => $location]);
    }
}
