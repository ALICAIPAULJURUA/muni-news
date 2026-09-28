<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\Newsletter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsletterSocialsSmokeTest extends TestCase
{
    use RefreshDatabase;

    private function makeNewsletter(array $overrides = []): Newsletter
    {
        return Newsletter::create(array_merge([
            'title' => 'Smoke Test Newsletter',
            'slug' => 'smoke-test-newsletter-' . str()->random(6),
            'description' => 'A summary',
            'content' => '<p>Hello content</p>',
            'publication_year' => 2026,
            'file_path' => 'newsletters/sample.pdf',
            'is_published' => true,
        ], $overrides));
    }

    public function test_newsletter_show_page_renders_with_socials_and_comments(): void
    {
        $user = User::factory()->create();
        $nl = $this->makeNewsletter(['author_id' => $user->id]);

        $nl->comments()->create([
            'user_id' => $user->id,
            'author_name' => 'Test User',
            'author_email' => 't@example.com',
            'content' => 'Nice issue',
            'is_approved' => true,
        ]);
        $nl->likes()->create(['user_id' => $user->id]);

        $resp = $this->get(route('newsletters.show', $nl->slug));
        $resp->assertOk();
        $resp->assertSee('Smoke Test Newsletter');
        $resp->assertSee('Hello content', false);
        $resp->assertSee('Comments (1)');
        $resp->assertSee('Nice issue');
        $resp->assertSee('Likes');
    }

    public function test_homepage_shows_latest_published_newsletters(): void
    {
        $user = User::factory()->create();
        $this->makeNewsletter(['title' => 'Summer Bulletin', 'author_id' => $user->id]);
        $this->makeNewsletter(['title' => 'Draft Issue', 'is_published' => false, 'author_id' => $user->id]);

        $resp = $this->get(route('home'));
        $resp->assertOk();
        $resp->assertSee('Latest Newsletters');
        $resp->assertSee('Summer Bulletin');
        $resp->assertDontSee('Draft Issue');
        $resp->assertSee('newsletter-cta-section');
        $resp->assertSee('Stay Informed');
    }

    public function test_like_toggle_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $cat = Category::create(['name' => 'News', 'slug' => 'news']);
        $article = Article::create([
            'author_id' => $user->id,
            'category_id' => $cat->id,
            'title' => 'Article One',
            'slug' => 'article-one',
            'summary' => 'Summary',
            'content' => 'Body',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $resp = $this->actingAs($user)->postJson(route('like.store'), [
            'likeable_type' => Article::class,
            'likeable_id' => $article->id,
        ]);
        $resp->assertOk()->assertJson(['liked' => true, 'count' => 1]);

        $resp = $this->actingAs($user)->postJson(route('like.store'), [
            'likeable_type' => Article::class,
            'likeable_id' => $article->id,
        ]);
        $resp->assertOk()->assertJson(['liked' => false, 'count' => 0]);
    }

    public function test_unauthenticated_like_returns_401(): void
    {
        $user = User::factory()->create();
        $nl = $this->makeNewsletter(['author_id' => $user->id]);

        $resp = $this->postJson(route('like.store'), [
            'likeable_type' => Newsletter::class,
            'likeable_id' => $nl->id,
        ]);
        $resp->assertStatus(401);
    }

    public function test_comment_store_is_polymorphic_for_newsletters(): void
    {
        $user = User::factory()->create();
        $nl = $this->makeNewsletter(['author_id' => $user->id]);

        $resp = $this->from(route('newsletters.show', $nl->slug))->post(route('comments.store'), [
            'commentable_type' => Newsletter::class,
            'commentable_id' => $nl->id,
            'author_name' => 'Reader',
            'author_email' => 'r@example.com',
            'content' => 'Great read!',
        ]);

        $resp->assertRedirect();
        $this->assertEquals(1, $nl->comments()->count());
        $this->assertEquals('Great read!', $nl->comments()->first()->content);
    }

    public function test_news_and_newsletters_are_strictly_isolated(): void
    {
        $user = User::factory()->create();
        $cat = Category::create(['name' => 'News', 'slug' => 'news']);

        $article = Article::create([
            'author_id' => $user->id,
            'category_id' => $cat->id,
            'title' => 'Exclusive Campus News Story',
            'slug' => 'exclusive-campus-news-story',
            'summary' => 'A news story only.',
            'content' => '<p>News body</p>',
            'is_published' => true,
            'published_at' => now(),
        ]);

        $newsletter = $this->makeNewsletter([
            'title' => 'Quarterly Research Digest',
            'author_id' => $user->id,
        ]);

        // Homepage: both the article (news) and the "Latest Newsletters" section appear
        $home = $this->get(route('home'));
        $home->assertOk();
        $home->assertSee('Exclusive Campus News Story');
        $home->assertSee('Latest Newsletters');
        $home->assertSee('Quarterly Research Digest');

        // /news: article appears, newsletter does NOT
        $news = $this->get(route('news.index'));
        $news->assertOk();
        $news->assertSee('Exclusive Campus News Story');
        $news->assertDontSee('Quarterly Research Digest');

        // /newsletters: newsletter appears, article does NOT
        $newsletters = $this->get(route('newsletters.index'));
        $newsletters->assertOk();
        $newsletters->assertSee('Quarterly Research Digest');
        $newsletters->assertDontSee('Exclusive Campus News Story');
    }

    public function test_newsletters_index_uses_news_card_grid(): void
    {
        $user = User::factory()->create();
        $newsletter = $this->makeNewsletter(['title' => 'Campus Digest', 'author_id' => $user->id]);

        $resp = $this->get(route('newsletters.index'));
        $resp->assertOk();
        $resp->assertSee('grid md:grid-cols-2 lg:grid-cols-3 gap-6');
        $resp->assertSee('class="card-muni"', false);
        $resp->assertSee('Campus Digest');
        $resp->assertSee('Newsletter');
        $resp->assertSee('card-title');
        $resp->assertSee('card-excerpt');
        $resp->assertSee("href=\"" . route('newsletters.show', $newsletter->slug) . "\"", false);
    }

    public function test_style_and_script_tags_are_preserved_from_admin_newsletter_to_frontend(): void
    {
        foreach (['super_admin', 'comm_admin', 'editor', 'viewer'] as $role) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => $role]);
        }
        $user = User::factory()->create();
        $user->assignRole('super_admin');

        $html = "<style>\n"
            . '.test-box { background: 8B0000; color: white; padding: 20px; border-radius: 8px; }' . "\n"
            . '</style>' . "\n"
            . '<script>document.body.dataset.spy = "ok";</script>' . "\n"
            . '<div class="test-box"><h2>Test Newsletter</h2><p>Styled content</p></div>';

        $resp = $this->actingAs($user)->post(route('admin.newsletters.store'), [
            'title' => 'Styled Newsletter',
            'content' => $html,
            'is_published' => '1',
        ]);
        $resp->assertRedirect(route('admin.newsletters.index'));

        $newsletter = Newsletter::where('slug', 'styled-newsletter')->first();
        $this->assertNotNull($newsletter);
        $this->assertStringContainsString('<style>', $newsletter->content);
        $this->assertStringContainsString('<script>', $newsletter->content);

        $show = $this->get(route('newsletters.show', $newsletter->slug));
        $show->assertOk();
        $show->assertSee($html, false);
        $show->assertSee('.test-box', false);
    }
}