<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EventRequest;
use App\Models\Event;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request): View
    {
        $events = Event::latest('event_date')->paginate(15);
        return view('admin.events.index', compact('events'));
    }

    public function create(): View { return view('admin.events.create'); }
    public function store(EventRequest $request, ImageService $imageService): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['title']);
        $orig=$data['slug']; $i=1; while(Event::where('slug',$data['slug'])->exists()) $data['slug']=$orig.'-'.$i++;
        if($request->hasFile('featured_image')){
            $data['featured_image']=$imageService->storeImage($request->file('featured_image'),'events');
        }
        $event = Event::create($data);
        Log::info('Event saved:', $event->toArray());
        return redirect()->route('admin.events.index')->with('success','Event created.');
    }
    public function edit(Event $event): View { return view('admin.events.edit', compact('event')); }
    public function update(EventRequest $request, Event $event, ImageService $imageService): RedirectResponse
    {
        $data=$request->validated();
        $data['slug']=!empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['title']);
        $orig=$data['slug']; $i=1; while(Event::where('slug',$data['slug'])->where('id','!=',$event->id)->exists()) $data['slug']=$orig.'-'.$i++;
        if($request->hasFile('featured_image')) $data['featured_image']=$imageService->storeImage($request->file('featured_image'),'events');
        else unset($data['featured_image']);
        $event->update($data);
        return redirect()->route('admin.events.index')->with('success','Event updated.');
    }
    public function destroy(Event $event): RedirectResponse { $event->delete(); return back()->with('success','Event deleted.'); }
}
