<?php

use App\Mail\NewsletterPublishedMail;
use App\Models\Newsletter;
use App\Models\NewsletterSubscription;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    foreach (['super_admin', 'comm_admin', 'editor', 'viewer'] as $r) {
        Role::firstOrCreate(['name' => $r]);
    }
    Mail::fake();
});

test('publishing a newsletter emails every active subscriber with signed unsubscribe links', function () {
    $admin = User::factory()->create();
    $admin->assignRole('comm_admin');

    $active = NewsletterSubscription::create(['email' => 'one@example.com', 'is_active' => true, 'subscribed_at' => now()]);
    NewsletterSubscription::create(['email' => 'two@example.com', 'is_active' => true, 'subscribed_at' => now()]);
    NewsletterSubscription::create(['email' => 'ghost@example.com', 'is_active' => false, 'subscribed_at' => now()]);

    $this->actingAs($admin)->post(route('admin.newsletters.store'), [
        'title' => 'Quarterly Bulletin',
        'content' => '<p>Hello <strong>subscribers</strong>!</p>',
        'is_published' => '1',
    ])->assertRedirect(route('admin.newsletters.index'));

    $newsletter = Newsletter::first();

    Mail::assertSent(NewsletterPublishedMail::class, 2);
    Mail::assertSent(NewsletterPublishedMail::class, function (NewsletterPublishedMail $mail) use ($active, $newsletter) {
        return $mail->hasTo($active->email)
            && $mail->newsletter->is($newsletter)
            && $mail->newsletterUrl === route('newsletters.show', $newsletter->slug)
            && str_contains($mail->unsubscribeUrl, $active->email)
            && str_contains($mail->unsubscribeUrl, 'signature=');
    });
    Mail::assertNotSent(NewsletterPublishedMail::class, function (NewsletterPublishedMail $mail) {
        return $mail->hasTo('ghost@example.com');
    });
});

test('creating an unpublished newsletter sends no email', function () {
    $admin = User::factory()->create();
    $admin->assignRole('comm_admin');
    NewsletterSubscription::create(['email' => 'one@example.com', 'is_active' => true, 'subscribed_at' => now()]);

    $this->actingAs($admin)->post(route('admin.newsletters.store'), [
        'title' => 'Draft bulletin',
        'content' => '<p>Draft</p>',
    ]);

    Mail::assertNotSent(NewsletterPublishedMail::class);
});

test('updating to published emails subscribers once and re-saving does not resend', function () {
    $admin = User::factory()->create();
    $admin->assignRole('super_admin');
    NewsletterSubscription::create(['email' => 'one@example.com', 'is_active' => true, 'subscribed_at' => now()]);

    $newsletter = Newsletter::create([
        'title' => 'Draft Edition',
        'slug' => 'draft-edition-'.uniqid(),
        'content' => '<p>Draft content</p>',
        'publication_year' => 2026,
        'is_published' => false,
    ]);

    $payload = ['title' => $newsletter->title, 'content' => $newsletter->content, 'is_published' => '1'];

    $this->actingAs($admin)->put(route('admin.newsletters.update', $newsletter), $payload);
    $this->actingAs($admin)->put(route('admin.newsletters.update', $newsletter), $payload);

    Mail::assertSent(NewsletterPublishedMail::class, 1);
});

test('unsubscribe confirmation page requires a valid signed url', function () {
    $this->get('/unsubscribe/jane@example.com')->assertStatus(403);

    $url = URL::signedRoute('unsubscribe.show', ['email' => 'jane@example.com']);
    $this->get($url)->assertStatus(200)->assertSee('jane@example.com');
});

test('unsubscribing deactivates the subscription and requires a valid signature', function () {
    $sub = NewsletterSubscription::create(['email' => 'jane@example.com', 'is_active' => true, 'subscribed_at' => now()]);

    $this->post('/unsubscribe/jane@example.com')->assertStatus(403);

    $url = URL::signedRoute('unsubscribe.process', ['email' => 'jane@example.com']);
    $res = $this->post($url);
    $res->assertStatus(200);
    $res->assertSee('successfully unsubscribed');

    $this->assertFalse($sub->fresh()->is_active);
});

test('unsubscribing an unknown email still shows success without error', function () {
    $url = URL::signedRoute('unsubscribe.process', ['email' => 'nobody@example.com']);
    $this->post($url)->assertStatus(200)->assertSee('successfully unsubscribed');
});

test('newsletter published email renders with branded markup and links', function () {
    $newsletter = Newsletter::create([
        'title' => 'Quarterly Bulletin',
        'slug' => 'quarterly-'.uniqid(),
        'content' => '<p>Hello subscribers! This is the bulletin excerpt.</p>',
        'publication_year' => 2026,
        'is_published' => true,
    ]);

    $mail = new NewsletterPublishedMail(
        $newsletter,
        'http://localhost/newsletters/'.$newsletter->slug,
        'http://localhost/unsubscribe/jane%40example.com?expires=123&signature=abc'
    );

    $html = $mail->render();

    expect($html)
        ->toContain('Quarterly Bulletin')
        ->toContain('#8B0000')
        ->toContain('Hello subscribers! This is the bulletin excerpt')
        ->toContain('http://localhost/newsletters/'.$newsletter->slug)
        ->toContain('http://localhost/unsubscribe/jane%40example.com');
});