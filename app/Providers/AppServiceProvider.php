<?php

namespace App\Providers;

use App\Models\SectionPattern;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share the active section patterns (image, opacity, blend mode) with the
        // public layout so sections can auto-blend their background patterns.
        View::composer('layouts.app', function ($view) {
            $view->with('activePatterns', SectionPattern::where('is_active', true)->get()->keyBy('section_slug'));
        });
    }
}
