<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Frontend\ArticleController;
use App\Http\Controllers\Frontend\DownloadController;
use App\Http\Controllers\Frontend\EventController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\MediaController;
use App\Http\Controllers\Frontend\NewsletterController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\SubscribeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public Frontend
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/news', [ArticleController::class, 'index'])->name('news.index');
Route::get('/article/{slug}', [ArticleController::class, 'show'])->middleware('track.views')->name('article.show');
Route::get('/category/{slug}', [ArticleController::class, 'category'])->name('category.show');
Route::get('/newsletters', [NewsletterController::class, 'index'])->name('newsletters.index');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/event/{slug}', [EventController::class, 'show'])->name('event.show');
Route::get('/media-gallery', [MediaController::class, 'index'])->name('gallery.index');
Route::get('/downloads', [DownloadController::class, 'index'])->name('downloads.index');
Route::get('/downloads/{download}/file', [DownloadController::class, 'download'])->name('downloads.download');
Route::post('/subscribe', [SubscribeController::class, 'store'])->middleware('throttle:3,1')->name('subscribe');
// Static pages: about, contact, etc.
Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');
Route::get('/about', fn() => app(PageController::class)->show('about'))->name('about');
Route::get('/contact', fn() => app(PageController::class)->show('contact'))->name('contact');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin panel - protected by role middleware
Route::middleware(['auth', 'role:super_admin|comm_admin|editor'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
