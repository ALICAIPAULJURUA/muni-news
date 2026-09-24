<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewsletterRequest;
use App\Models\Newsletter;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\Request;

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
        $data=$request->validated();
        $data['slug']=!empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['title']);
        $orig=$data['slug']; $i=1; while(Newsletter::where('slug',$data['slug'])->exists()) $data['slug']=$orig.'-'.$i++;
        if($request->hasFile('cover_image')) $data['cover_image']=$imageService->storeImage($request->file('cover_image'),'newsletters');
        if($request->hasFile('file')) $data['file_path']=$imageService->storeDocument($request->file('file'),'newsletters');
        unset($data['file']);
        Newsletter::create($data);
        return redirect()->route('admin.newsletters.index')->with('success','Newsletter created.');
    }
    public function edit(Newsletter $newsletter): View { return view('admin.newsletters.edit', compact('newsletter')); }
    public function update(NewsletterRequest $request, Newsletter $newsletter, ImageService $imageService): RedirectResponse
    {
        $data=$request->validated();
        $data['slug']=!empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['title']);
        $orig=$data['slug']; $i=1; while(Newsletter::where('slug',$data['slug'])->where('id','!=',$newsletter->id)->exists()) $data['slug']=$orig.'-'.$i++;
        if($request->hasFile('cover_image')) $data['cover_image']=$imageService->storeImage($request->file('cover_image'),'newsletters');
        else unset($data['cover_image']);
        if($request->hasFile('file')) $data['file_path']=$imageService->storeDocument($request->file('file'),'newsletters');
        unset($data['file']);
        $newsletter->update($data);
        return redirect()->route('admin.newsletters.index')->with('success','Newsletter updated.');
    }
    public function destroy(Newsletter $newsletter): RedirectResponse { $newsletter->delete(); return back()->with('success','Newsletter deleted.'); }
}
