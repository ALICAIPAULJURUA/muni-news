<?php

namespace App\Mail;

use App\Models\Newsletter;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterPublishedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Newsletter $newsletter;

    public string $newsletterUrl;

    public string $unsubscribeUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Newsletter $newsletter, string $newsletterUrl, string $unsubscribeUrl)
    {
        $this->newsletter = $newsletter;
        $this->newsletterUrl = $newsletterUrl;
        $this->unsubscribeUrl = $unsubscribeUrl;
    }

    public function build(): self
    {
        return $this->subject('New Newsletter: ' . $this->newsletter->title)
            ->view('emails.newsletter-published')
            ->with([
                'newsletter' => $this->newsletter,
                'newsletterUrl' => $this->newsletterUrl,
                'unsubscribeUrl' => $this->unsubscribeUrl,
            ]);
    }
}