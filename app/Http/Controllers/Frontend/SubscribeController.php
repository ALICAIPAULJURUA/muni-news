<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\NewsletterWelcomeMail;
use App\Models\NewsletterSubscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SubscribeController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:newsletter_subscriptions,email'],
        ]);

        $subscription = NewsletterSubscription::create([
            'email' => $request->email,
            'is_active' => true,
            'subscribed_at' => now(),
        ]);

        // Send branded welcome email
        try {
            Mail::to($subscription->email)->send(new NewsletterWelcomeMail($subscription->email));
        } catch (\Exception $e) {
            Log::error('Welcome email failed for '.$subscription->email.': '.$e->getMessage());
            // Still return success for subscription, even if email fails
        }

        return back()->with('success', 'Successfully subscribed to our newsletter! Check your email for a welcome message.');
    }
}
