<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Article;
use App\Models\Category;
use App\Models\Event;
use App\Models\Page;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate sitemap.xml for Muni News Portal';

    public function handle(): int
    {
        $sitemap = Sitemap::create();

        // Home
        $sitemap->add(Url::create(url('/'))->setPriority(1.0));
        $sitemap->add(Url::create(route('news.index'))->setPriority(0.9));
        $sitemap->add(Url::create(route('newsletters.index'))->setPriority(0.8));
        $sitemap->add(Url::create(route('events.index'))->setPriority(0.8));
        $sitemap->add(Url::create(route('gallery.index'))->setPriority(0.7));
        $sitemap->add(Url::create(route('downloads.index'))->setPriority(0.7));

        // Articles
        Article::where('is_published', true)->latest('published_at')->each(function($article) use($sitemap){
            $sitemap->add(
                Url::create(route('article.show', $article->slug))
                    ->setLastModificationDate($article->updated_at)
                    ->setPriority(0.8)
            );
        });

        // Categories
        Category::all()->each(function($cat) use($sitemap){
            $sitemap->add(Url::create(route('category.show', $cat->slug))->setPriority(0.6));
        });

        // Events
        Event::all()->each(function($event) use($sitemap){
            $sitemap->add(Url::create(route('event.show', $event->slug))->setPriority(0.6));
        });

        // Pages
        Page::all()->each(function($page) use($sitemap){
            $sitemap->add(Url::create(route('page.show', $page->slug))->setPriority(0.5));
        });

        // Static pages
        if (Page::where('slug','about')->exists()) $sitemap->add(Url::create(url('/about'))->setPriority(0.5));
        if (Page::where('slug','contact')->exists()) $sitemap->add(Url::create(url('/contact'))->setPriority(0.5));

        $path = public_path('sitemap.xml');
        $sitemap->writeToFile($path);

        $count = count($sitemap->getTags());
        $this->info("Sitemap generated at $path with $count URLs");

        return self::SUCCESS;
    }
}
