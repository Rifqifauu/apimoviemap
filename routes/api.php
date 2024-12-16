<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReviewController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register',[UserController::class, 'store']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::group(['prefix' => 'reviews'], function () {
    Route::post('/', [ReviewController::class, 'store']);
    Route::get('/', [ReviewController::class, 'index']);
    Route::get('/{id}', [ReviewController::class, 'show']);
    Route::put('/{id}', [ReviewController::class, 'update']);
    Route::delete('/{id}', [ReviewController::class, 'destroy']);
});
use App\Http\Controllers\WatchlistController;

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


