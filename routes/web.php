<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\LocalizationController;

// Guest routes - anyone can browse these without logging in
Route::get('/', [ListingController::class, 'index'])->name('home');
// NOTE: this has to come BEFORE /listings/{listing}, otherwise Laravel thinks
// "create" is a listing id. It still needs auth, so we attach the middleware here.
Route::get('/listings/create', [ListingController::class, 'create'])->middleware('auth')->name('listings.create');
Route::get('/listings/{listing}', [ListingController::class, 'show'])->name('listings.show');
Route::get('/search', [ListingController::class, 'search'])->name('listings.search');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// Auth routes - login/register forms (GET) and the form submissions (POST)
Route::get('/register', [UserController::class, 'registerForm'])->name('register');
Route::post('/register', [UserController::class, 'register']);
Route::get('/login', [UserController::class, 'loginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Logged in users - everything in here is behind the 'auth' middleware
Route::middleware('auth')->group(function() {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::post('/listings', [ListingController::class, 'store'])->name('listings.store');
    Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->name('listings.edit');
    Route::put('/listings/{listing}', [ListingController::class, 'update'])->name('listings.update');
    Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->name('listings.destroy');
    Route::delete('/images/{image}', [ImageController::class, 'destroy'])->name('images.destroy');
});

// Admin only - needs to be logged in AND pass the admin check
Route::middleware(['auth', 'admin'])->group(function() {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/admin/users/{user}/block', [AdminController::class, 'blockUser'])->name('admin.blockUser');
    Route::post('/admin/users/{user}/unblock', [AdminController::class, 'unblockUser'])->name('admin.unblockUser');
    Route::post('/admin/listings/{listing}/restore', [AdminController::class, 'restoreListing'])->name('admin.restoreListing');
    Route::get('/admin/audit-logs', [AdminController::class, 'auditLogs'])->name('admin.auditLogs');
});

// Language switch - flips between EN and LV, then sends you back
Route::get('/lang/{lang}', [LocalizationController::class, 'switchLanguage'])->name('lang.switch');