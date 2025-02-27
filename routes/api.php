<?php


use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/users', UserController::class);
    Route::post('/update', [UserController::class, 'changePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('posts', PostController::class);
    Route::get('postall', [PostController::class, 'posts']);

    Route::apiResource('comments', CommentController::class);
    Route::post('/posts/{postId}/comments', [CommentController::class, 'store']);

    
    Route::post('/posts/{postId}/like', [LikeController::class, 'LikePost']);
    Route::delete('/posts/{postId}/unlike', [LikeController::class, 'Unlike']);
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

