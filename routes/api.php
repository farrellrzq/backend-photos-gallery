<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;

// Register & Login
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Route
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('photos', PhotoController::class);
    Route::get('photos/{photo}/download', [PhotoController::class, 'download']);

    Route::post('photos/{photo}/like', [LikeController::class, 'toggleLike']);

    Route::post('photos/{photo}/comment', [CommentController::class, 'store']);
    Route::delete('comments/{comment}', [CommentController::class, 'destroy']);
});
