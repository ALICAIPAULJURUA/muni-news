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
}