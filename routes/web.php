<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UsageController;
use App\Http\Controllers\StockUsageController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('logout', [LoginController::class, 'logout'])->name('auth.logout');

Route::prefix('auth')->middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showForm'])->name('login');
    Route::post('login', [LoginController::class, 'login'])->name('auth.login.submit');

    Route::get('register', [RegisterController::class, 'showForm'])->name('auth.register');
    Route::post('register', [RegisterController::class, 'register'])->name('auth.register.submit');

    Route::get('forgot-password', [ForgotPasswordController::class, 'showForm'])->name('auth.forgotpass');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('auth.forgotpass.submit');

    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showForm'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::get('/auth/reset-password', function () {
    return redirect('auth/login')->withErrors(['message' => 'Akses tidak valid.']);
});

Route::resource('items', ItemController::class);

Route::resource('category', CategoryController::class);

Route::resource('usage', UsageController::class);

Route::resource('settings', SettingsController::class)->only(['index']);

Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

// usage.php
Route::middleware(['auth'])->group(function () {
    Route::get('/usage', [UsageController::class, 'index'])->name('usage.index');
    Route::post('/usage/ambil/{item}', [UsageController::class, 'ambil'])->name('usage.ambil');
    Route::post('/usage/ambil-custom/{item}', [UsageController::class, 'ambilCustom'])->name('usage.ambil.custom');
    Route::get('/usage/item/{item}', [UsageController::class, 'getItemDetails'])->name('usage.item.details');
    Route::get('/usage/history', [UsageController::class, 'history'])->name('usage.history');
});

Route::resource('history', StockUsageController::class)->except(['show']);

Route::get('/history/add', [StockUsageController::class, 'add'])->name('history.add');

Route::post('/history/destroyMultiple', [StockUsageController::class, 'destroyMultiple'])->name('history.destroyMultiple');

Route::post('history/delete-multiple', [StockUsageController::class, 'deleteMultiple'])->name('history.deleteMultiple');

Route::delete('/history/delete/{data}', [StockUsageController::class, 'delete'])->name('history.delete');

Route::get('/ajax-search', [App\Http\Controllers\SearchController::class, 'ajaxSearch'])->name('ajax.search');

Route::get('/chart/item-data', [ChartController::class, 'itemData']);

Route::get('/chart/top-category', [ChartController::class, 'topCategoryPie']);
