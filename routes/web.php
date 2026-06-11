<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LocalizationController;

Route::get('/', [ListingController::class, 'index'])->name('home');
// has to be before /{listing} or Laravel treats "create" as an id
Route::get('/listings/create', [ListingController::class, 'create'])->middleware('auth')->name('listings.create');
Route::get('/listings/{listing}', [ListingController::class, 'show'])->name('listings.show');
Route::get('/search', [ListingController::class, 'search'])->name('listings.search');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('/register', [UserController::class, 'registerForm'])->name('register');
Route::post('/register', [UserController::class, 'register']);
Route::get('/login', [UserController::class, 'loginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function() {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::post('/listings', [ListingController::class, 'store'])->name('listings.store');
    Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->name('listings.edit');
    Route::put('/listings/{listing}', [ListingController::class, 'update'])->name('listings.update');
    Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->name('listings.destroy');
    Route::delete('/images/{image}', [ImageController::class, 'destroy'])->name('images.destroy');
});

Route::middleware(['auth', 'admin'])->group(function() {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/admin/users/{user}/block', [AdminController::class, 'blockUser'])->name('admin.blockUser');
    Route::post('/admin/users/{user}/unblock', [AdminController::class, 'unblockUser'])->name('admin.unblockUser');
    // withTrashed() needed or binding 404s on soft-deleted listings
    Route::post('/admin/listings/{listing}/restore', [AdminController::class, 'restoreListing'])->name('admin.restoreListing')->withTrashed();
    Route::get('/admin/audit-logs', [AdminController::class, 'auditLogs'])->name('admin.auditLogs');
});

Route::get('/lang/{lang}', [LocalizationController::class, 'switchLanguage'])->name('lang.switch');
