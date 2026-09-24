<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubscriberController extends Controller
{
    public function index(): View
    {
        $subscribers = NewsletterSubscription::latest('subscribed_at')->paginate(20);
        return view('admin.subscribers.index', compact('subscribers'));
    }

    public function destroy(NewsletterSubscription $subscriber): RedirectResponse
    {
        $subscriber->delete();
        return back()->with('success','Subscriber deleted.');
    }

    public function export(): StreamedResponse
    {
        $subscribers = NewsletterSubscription::all();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="subscribers_'.date('Y-m-d').'.csv"',
        ];
        return response()->stream(function() use($subscribers){
            $handle = fopen('php://output','w');
            fputcsv($handle, ['Email','Is Active','Subscribed At']);
            foreach($subscribers as $s){
                fputcsv($handle, [$s->email, $s->is_active ? 'yes':'no', $s->subscribed_at]);
            }
            fclose($handle);
        }, 200, $headers);
    }
}
