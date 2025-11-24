<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UsageController;
use App\Http\Controllers\StockUsageController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.register');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('items', ItemController::class);

Route::resource('category', CategoryController::class);

Route::resource('usage', UsageController::class);

Route::resource('settings', SettingsController::class);

Route::post('/usage/{id}/ambil', [UsageController::class, 'ambil'])->name('usage.ambil');

Route::resource('history', StockUsageController::class)->except(['show']);

Route::get('/history/add', [StockUsageController::class, 'add'])->name('history.add');

Route::post('/history/destroyMultiple', [StockUsageController::class, 'destroyMultiple'])->name('history.destroyMultiple');

Route::post('history/delete-multiple', [StockUsageController::class, 'deleteMultiple'])->name('history.deleteMultiple');

Route::delete('/history/delete/{data}', [StockUsageController::class, 'delete'])->name('history.delete');

Route::get('/ajax-search', [App\Http\Controllers\SearchController::class, 'ajaxSearch'])->name('ajax.search');

Route::get('/chart/item-data', [ChartController::class, 'itemData']);

Route::get('/chart/top-category', [ChartController::class, 'topCategoryPie']);

require __DIR__ . '/auth.php';
