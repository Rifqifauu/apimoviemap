<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\FavoriteController;

// Auth Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [UserController::class, 'store']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Reviews Routes
Route::group(['prefix' => 'reviews'], function () {
    Route::post('/', [ReviewController::class, 'store']);
    Route::get('/', [ReviewController::class, 'index']);
    Route::get('/{id}', [ReviewController::class, 'show']);
    Route::put('/{id}', [ReviewController::class, 'update']);
    Route::delete('/{id}', [ReviewController::class, 'destroy']);
});

// Watchlists Routes
Route::prefix('watchlists')->group(function () {
    // Menampilkan semua watchlist milik user tertentu
    Route::get('/{user_id}', [WatchlistController::class, 'index'])->name('watchlists.index');

    // Menambah item ke watchlist
    Route::post('/', [WatchlistController::class, 'store'])->name('watchlists.store');

    // Mengedit item di watchlist berdasarkan ID item
    Route::put('/{id}', [WatchlistController::class, 'update'])->name('watchlists.update');

    // Menghapus item dari watchlist berdasarkan ID item
    Route::delete('/{user_id}/{film_id}', [WatchlistController::class, 'destroy'])->name('watchlists.destroy');
});

// Edit Profile Routes
Route::middleware('auth:sanctum')->prefix('favorites')->group(function () {
    Route::get('/{user_id}', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::put('/{id}', [FavoriteController::class, 'update'])->name('favorites.update');
    Route::delete('/{user_id}/{film_id}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
});

// Edit Profile Routes - FIXED (Moved outside favorites group)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user-profile', [UserProfileController::class, 'show']);
    Route::put('/user-profile', [UserProfileController::class, 'update']);
});

// Reviews Route
Route::get('/reviews/user/{user_id}', [ReviewController::class, 'getUserReviews']);





//Favorite Routes
Route::prefix('favorites')->group(function () {
    // Menampilkan semua favorite milik user tertentu
    Route::get('/{user_id}', [FavoriteController::class, 'index'])->name('favorites.index');

    // Menambah item ke favorite
    Route::post('/', [FavoriteController::class, 'store'])->name('favorites.store');

    // Mengedit item di favorite berdasarkan ID item
    Route::put('/{id}', [FavoriteController::class, 'update'])->name('favorites.update');

    // Menghapus item dari favorite berdasarkan ID item
    Route::delete('/{user_id}/{film_id}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
});

