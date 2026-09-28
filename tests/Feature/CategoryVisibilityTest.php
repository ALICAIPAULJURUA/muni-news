<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryVisibilityTest extends TestCase
{
    use RefreshDatabase;

    private function seedCategories(): void
    {
        $cats = [
            'news' => 'News',
            'announcements' => 'Announcements',
            'events' => 'Events',
            'research' => 'Research',
            'student-stories' => 'Student Stories',
            'staff-stories' => 'Staff Stories',
        ];
        foreach ($cats as $slug => $name) {
            Category::create(['name' => $name, 'slug' => $slug]);
        }
    }

    private function seedArticles(): void
    {
        $user = User::factory()->create();

        // A published article in a visible category so at least one homepage
        // highlight section renders.
        $visible = Category::where('slug', 'announcements')->first();
        Article::create([
            'author_id' => $user->id,
            'category_id' => $visible->id,
            'title' => 'Visible announcement',
            'slug' => 'visible-announcement',
            'summary' => 'Summary',
            'content' => 'Body',
            'is_published' => true,
            'published_at' => now(),
        ]);

        foreach (['research', 'student-stories', 'staff-stories'] as $slug) {
            $cat = Category::where('slug', $slug)->first();
            Article::create([
                'author_id' => $user->id,
                'category_id' => $cat->id,
                'title' => 'Hidden story in ' . $slug,
                'slug' => 'hidden-story-' . $slug,
                'summary' => 'Summary',
                'content' => 'Body',
                'is_published' => true,
                'published_at' => now(),
            ]);
        }
    }

    public function test_homepage_nav_and_footer_hide_research_student_and_staff_categories(): void
    {
        $this->seedCategories();
        $this->seedArticles();

        $resp = $this->get(route('home'));
        $resp->assertOk();

        // Visible category renders a homepage highlight section.
        $resp->assertSee('<h3 class="font-bold text-lg"', false);
        $resp->assertSee(route('category.show', 'announcements'));
        $resp->assertSee('Latest News');

        // Hidden categories never render a link (nav, Mobile menu, More dropdown,
        // footer, or homepage highlight sections).
        foreach (['research', 'student-stories', 'staff-stories'] as $slug) {
            $resp->assertDontSee(route('category.show', $slug));
        }
    }

    public function test_news_filter_does_not_list_hidden_categories(): void
    {
        $this->seedCategories();

        $resp = $this->get(route('news.index'));
        $resp->assertOk();

        $resp->assertSee('value="announcements"', false);
        foreach (['research', 'student-stories', 'staff-stories'] as $slug) {
            $resp->assertDontSee('value="' . $slug . '"', false);
        }
    }
}