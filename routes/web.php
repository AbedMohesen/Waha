<?php

use App\Http\Controllers\Dashboard\MartyrController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'index'])->name('front.index');
Route::get('/about', [MainController::class, 'about'])->name('front.about');
Route::get('/contact', [MainController::class, 'contact'])->name('front.contact');
Route::get('/martyr_search', [MainController::class, 'martyr_search'])->name('front.search');
Route::get('/search', [MainController::class, 'search'])->name('search');
Route::get('/martyr/{id?}', [MainController::class, 'martyr'])->name('martyr');
// Route::get('/import', function () {
//     return view('import');
// })->name('import');
// Route::post('/import', [ExcelController::class, 'import']);

Route::prefix('dashboard')->name('dashboard.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('index');
    Route::resource('/martyr', MartyrController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
