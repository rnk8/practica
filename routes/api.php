<?php

use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;



// Rutas públicas
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    // Usuario actual
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Posts
    Route::apiResource('posts', PostController::class);
    Route::apiResource('users', UserController::class);
    
    // Comentarios (mediante rutas anidadas)
    Route::apiResource('posts.comments', CommentController::class)->shallow();
    
    // Likes
    Route::post('posts/{post}/like', [LikeController::class, 'likePost']);
    Route::delete('posts/{post}/like', [LikeController::class, 'unlikePost']);
    Route::post('comments/{comment}/like', [LikeController::class, 'likeComment']);
    Route::delete('comments/{comment}/like', [LikeController::class, 'unlikeComment']);
});