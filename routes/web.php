<?php

use App\Http\Controllers\Dashboard\CondolenceController as DashboardCondolenceController;
use App\Http\Controllers\Dashboard\HomepageContentController;
use App\Http\Controllers\Dashboard\MartyrCondolenceController;
use App\Http\Controllers\Dashboard\MartyrController;
use App\Http\Controllers\Dashboard\MomeriesImgController;
use App\Http\Controllers\Dashboard\ProfileImgController;
use App\Http\Controllers\Dashboard\StoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicCondolenceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'index'])->name('front.index');
Route::get('/about', [MainController::class, 'about'])->name('front.about');
Route::get('/contact', [MainController::class, 'contact'])->name('front.contact');
Route::get('/martyr_search', [MainController::class, 'martyr_search'])->name('front.search');
Route::get('/search', [MainController::class, 'search'])->name('search');
Route::get('/martyr/{martyr?}', [MainController::class, 'martyr'])->name('martyr');
Route::post('/martyr/{martyr}/condolences', [PublicCondolenceController::class, 'store'])
    ->middleware('throttle:condolences')
    ->name('martyr.condolences.store');
// Route::get('/import', function () {
//     return view('import');
// })->name('import');
// Route::post('/import', [ExcelController::class, 'import']);

Route::prefix('dashboard')->name('dashboard.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('index');
    Route::resource('/martyr', MartyrController::class);
    Route::post('/martyr/{martyr}/story', [StoryController::class, 'store'])
        ->name('martyr.story.store');
    Route::put('/martyr/{martyr}/story/{story}', [StoryController::class, 'update'])
        ->name('martyr.story.update');
    Route::delete('/martyr/{martyr}/story/{story}', [StoryController::class, 'destroy'])
        ->name('martyr.story.destroy');
    Route::put('/martyr/{martyr}/profile-image', [ProfileImgController::class, 'update'])
        ->name('martyr.profile-image.update');
    Route::delete('/martyr/{martyr}/profile-image', [ProfileImgController::class, 'destroy'])
        ->name('martyr.profile-image.destroy');
    Route::post('/martyr/{martyr}/memories', [MomeriesImgController::class, 'store'])
        ->name('martyr.memories.store');
    Route::delete('/martyr/{martyr}/memories/{memory}', [MomeriesImgController::class, 'destroy'])
        ->name('martyr.memories.destroy');
    Route::patch('/martyr/{martyr}/condolences/{condolence}/approve', [MartyrCondolenceController::class, 'approve'])
        ->name('martyr.condolences.approve');
    Route::delete('/martyr/{martyr}/condolences/{condolence}', [MartyrCondolenceController::class, 'destroy'])
        ->name('martyr.condolences.destroy');
    Route::get('/condolences', [DashboardCondolenceController::class, 'index'])
        ->name('condolences.index');
    Route::patch('/condolences/{condolence}/approve', [DashboardCondolenceController::class, 'approve'])
        ->name('condolences.approve');
    Route::delete('/condolences/{condolence}', [DashboardCondolenceController::class, 'reject'])
        ->name('condolences.reject');
    Route::get('/homepage-content', [HomepageContentController::class, 'index'])
        ->name('homepage-content.index');
    Route::post('/homepage-content/{section}', [HomepageContentController::class, 'store'])
        ->name('homepage-content.store');
    Route::delete('/homepage-content/{featuredContent}', [HomepageContentController::class, 'destroy'])
        ->name('homepage-content.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
