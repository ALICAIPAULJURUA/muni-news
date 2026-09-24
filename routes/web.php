<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Frontend\ArticleController;
use App\Http\Controllers\Frontend\DownloadController;
use App\Http\Controllers\Frontend\EventController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\MediaController;
use App\Http\Controllers\Frontend\NewsletterController;
use App\Http\Controllers\Frontend\CommentController as FrontendCommentController;
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
Route::post('/comments', [FrontendCommentController::class, 'store'])->name('comments.store');
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

    // Articles - all editors can manage
    Route::post('/upload-image', [\App\Http\Controllers\Admin\ArticleController::class, 'uploadImage'])->name('upload-image');
    Route::get('/articles/{article}/preview', [\App\Http\Controllers\Admin\ArticleController::class, 'preview'])->name('articles.preview');
    Route::resource('articles', \App\Http\Controllers\Admin\ArticleController::class)->only(['index','create','store','edit','update','destroy']);

    // Categories - only super_admin & comm_admin
    Route::middleware('role:super_admin|comm_admin')->group(function(){
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->only(['index','store','update','destroy']);
        Route::resource('newsletters', \App\Http\Controllers\Admin\NewsletterAdminController::class)->only(['index','create','store','edit','update','destroy']);
        Route::resource('downloads', \App\Http\Controllers\Admin\DownloadAdminController::class)->only(['index','create','store','edit','update','destroy']);
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class,'index'])->name('settings.index');
        Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class,'update'])->name('settings.update');
        Route::post('/settings/test-email', [\App\Http\Controllers\Admin\SettingController::class,'testEmail'])->name('settings.test-email');
    });

    Route::resource('events', \App\Http\Controllers\Admin\EventController::class)->only(['index','create','store','edit','update','destroy']);

    // Users - only super_admin (enforced in Request + route middleware)
    Route::middleware('role:super_admin')->group(function(){
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->only(['index','create','store','edit','update','destroy']);
    });
    Route::post('/users/{user}/toggle-active', [\App\Http\Controllers\Admin\UserController::class,'toggleActive'])->name('users.toggle-active');

    // Media Library
    Route::get('/media', [\App\Http\Controllers\Admin\MediaLibraryController::class,'index'])->name('media.index');
    Route::post('/media', [\App\Http\Controllers\Admin\MediaLibraryController::class,'store'])->name('media.store');
    Route::delete('/media/{medium}', [\App\Http\Controllers\Admin\MediaLibraryController::class,'destroy'])->name('media.destroy');
    Route::post('/media/bulk-destroy', [\App\Http\Controllers\Admin\MediaLibraryController::class,'bulkDestroy'])->name('media.bulk-destroy');

    // Comments
    Route::get('/comments', [\App\Http\Controllers\Admin\CommentController::class,'index'])->name('comments.index');
    Route::patch('/comments/{comment}/approve', [\App\Http\Controllers\Admin\CommentController::class,'approve'])->name('comments.approve');
    Route::delete('/comments/{comment}', [\App\Http\Controllers\Admin\CommentController::class,'destroy'])->name('comments.destroy');

    // Subscribers
    Route::get('/subscribers', [\App\Http\Controllers\Admin\SubscriberController::class,'index'])->name('subscribers.index');
    Route::delete('/subscribers/{subscriber}', [\App\Http\Controllers\Admin\SubscriberController::class,'destroy'])->name('subscribers.destroy');
    Route::get('/subscribers/export/csv', [\App\Http\Controllers\Admin\SubscriberController::class,'export'])->name('subscribers.export');

    // Reports
    Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class,'index'])->name('reports.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
