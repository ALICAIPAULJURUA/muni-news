<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscription;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UnsubscribeController extends Controller
{
    // Show confirmation page
    public function show(Request $request, string $email): View
    {
        // Verify the signed URL
        if (! $request->hasValidSignature()) {
            abort(401, 'Invalid or expired unsubscribe link.');
        }

        return view('frontend.unsubscribe', compact('email'));
    }

    // Process the unsubscription
    public function process(Request $request, string $email): View
    {
        if (! $request->hasValidSignature()) {
            abort(401);
        }

        $subscription = NewsletterSubscription::where('email', $email)->first();

        if ($subscription) {
            $subscription->update(['is_active' => false]);
        }

        return view('frontend.unsubscribe-success', compact('email'));
    }
}