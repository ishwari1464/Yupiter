<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;

// Show admin login form
Route::get('admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login.form');

// Handle login POST
Route::post('admin/login', [AdminAuthController::class, 'login'])->name('admin.login');

// Protected admin routes
Route::middleware('auth:admin')->group(function () {
    // Admin dashboard
    Route::get('admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Logout
    Route::post('admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});
use App\Http\Controllers\Admin\AdminUserController;

// User CRUD routes
Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', AdminUserController::class);
});

//Offers management routes

use App\Http\Controllers\Admin\OfferController;

Route::middleware('auth:admin')->prefix('admin')->name('admin.')->group(function () {
    // Users CRUD
    Route::resource('users', AdminUserController::class);

    // Offers management
    Route::get('offers', [OfferController::class, 'index'])->name('offers.index');
    Route::post('offers', [OfferController::class, 'store'])->name('offers.store');
    Route::get('offers/toggle/{id}', [OfferController::class, 'toggle'])->name('offers.toggle');
    Route::delete('offers/{id}', [OfferController::class, 'destroy'])->name('offers.destroy');
});

