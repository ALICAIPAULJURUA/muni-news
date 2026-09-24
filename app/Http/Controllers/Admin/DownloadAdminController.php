<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DownloadRequest;
use App\Models\Download;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DownloadAdminController extends Controller
{
    public function index(): View
    {
        $downloads = Download::latest()->paginate(15);
        return view('admin.downloads.index', compact('downloads'));
    }
    public function create(): View { return view('admin.downloads.create'); }
    public function store(DownloadRequest $request, ImageService $imageService): RedirectResponse
    {
        $data=$request->validated();
        $data['slug']=!empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['title']);
        $orig=$data['slug']; $i=1; while(Download::where('slug',$data['slug'])->exists()) $data['slug']=$orig.'-'.$i++;
        if($request->hasFile('file')) $data['file_path']=$imageService->storeDocument($request->file('file'),'downloads');
        unset($data['file']);
        Download::create($data);
        return redirect()->route('admin.downloads.index')->with('success','Download created.');
    }
    public function edit(Download $download): View { return view('admin.downloads.edit', compact('download')); }
    public function update(DownloadRequest $request, Download $download, ImageService $imageService): RedirectResponse
    {
        $data=$request->validated();
        $data['slug']=!empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['title']);
        $orig=$data['slug']; $i=1; while(Download::where('slug',$data['slug'])->where('id','!=',$download->id)->exists()) $data['slug']=$orig.'-'.$i++;
        if($request->hasFile('file')) $data['file_path']=$imageService->storeDocument($request->file('file'),'downloads');
        unset($data['file']);
        $download->update($data);
        return redirect()->route('admin.downloads.index')->with('success','Download updated.');
    }
    public function destroy(Download $download): RedirectResponse { $download->delete(); return back()->with('success','Download deleted.'); }
}
