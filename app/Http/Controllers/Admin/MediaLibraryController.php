<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class MediaLibraryController extends Controller
{
    public function index(Request $request): View
    {
        $media = Media::with('uploader')->latest()->paginate(20);
        return view('admin.media.index', compact('media'));
    }

    public function store(Request $request, ImageService $imageService): RedirectResponse
    {
        $request->validate([
            'files.*' => ['required','file','max:5120'],
            'files' => ['required','array'],
        ]);

        foreach($request->file('files') as $file){
            $mime = $file->getMimeType();
            $ext = strtolower($file->getClientOriginalExtension());
            $path = null;
            if(str_starts_with($mime,'image/')){
                if(! $imageService->validateImage($file)) continue;
                $path = $imageService->storeImage($file, 'media');
            } else {
                $path = $imageService->storeDocument($file, 'media');
            }
            Media::create([
                'filename'=>basename($path),
                'original_name'=>$file->getClientOriginalName(),
                'path'=>$path,
                'mime_type'=>$mime,
                'size'=>$file->getSize(),
                'extension'=>$ext,
                'uploader_id'=>auth()->id(),
            ]);
        }

        return back()->with('success','Media uploaded.');
    }

    public function destroy(Media $medium): RedirectResponse
    {
        Storage::disk('public')->delete($medium->path);
        $medium->delete();
        return back()->with('success','Media deleted.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $ids = $request->input('ids', []);
        $media = Media::whereIn('id',$ids)->get();
        foreach($media as $m){
            Storage::disk('public')->delete($m->path);
            $m->delete();
        }
        return back()->with('success','Selected media deleted ('.count($media).').');
    }
}
