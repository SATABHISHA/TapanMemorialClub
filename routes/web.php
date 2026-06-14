<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MediaStreamController;
use App\Http\Controllers\Api\PerformanceApiController;
use App\Http\Controllers\Admin\AchievementController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DynamicPageController as AdminDynamicPageController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\MediaLibraryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PerformanceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SponsorController;
use App\Http\Controllers\Admin\SubmenuController;
use App\Http\Controllers\Admin\VlogController;
use App\Http\Controllers\BlogPageController;
use App\Http\Controllers\DynamicPageController;
use App\Http\Controllers\VlogPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/news', [BlogPageController::class, 'index'])->name('blogs.index');
Route::get('/news/{slug}', [BlogPageController::class, 'show'])->name('blogs.show');
Route::get('/vlogs', [VlogPageController::class, 'index'])->name('vlogs.index');
Route::get('/vlogs/{slug}', [VlogPageController::class, 'show'])->name('vlogs.show');
Route::get('/pages/{slug}', [DynamicPageController::class, 'show'])->name('pages.show');
Route::get('/media/{id}', [MediaStreamController::class, 'show'])->name('media.show');
Route::get('/media/{id}/thumb', [MediaStreamController::class, 'thumb'])->name('media.thumb');
Route::get('/api/performance-chart', [PerformanceApiController::class, 'chart'])->name('api.performance.chart');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user && $user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('home');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function (): void {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('menus', MenuController::class);
        Route::post('menus/reorder', [MenuController::class, 'reorder'])->name('menus.reorder');
        Route::resource('submenus', SubmenuController::class);
        Route::resource('sliders', SliderController::class);
        Route::resource('gallery', GalleryController::class);
        Route::post('performances/recap-text', [PerformanceController::class, 'updateRecapText'])->name('performances.recap-text');
        Route::post('performances/visibility', [PerformanceController::class, 'updateVisibility'])->name('performances.visibility');
        Route::resource('performances', PerformanceController::class);
        Route::resource('blogs', BlogController::class);
        Route::resource('dynamic-pages', AdminDynamicPageController::class)->except(['show', 'create', 'edit']);
        Route::resource('vlogs', VlogController::class);
        Route::resource('sponsors', SponsorController::class);
        Route::post('settings/bulk-update', [SettingController::class, 'bulkUpdate'])->name('settings.bulk-update');
        Route::post('settings/delete-key', [SettingController::class, 'destroyByKey'])->name('settings.delete-key');
        Route::resource('settings', SettingController::class);
        Route::resource('contacts', ContactController::class);
        Route::resource('achievements', AchievementController::class);
        Route::resource('media-library', MediaLibraryController::class);
    });

require __DIR__.'/auth.php';
