<?php

use App\Http\Controllers\Api\FeedController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Feed...
Route::get('/feed', [FeedController::class, 'index']);

// Internal...
Route::get('/posts', [PostController::class, 'index']);
Route::get('/write', [PostController::class, 'create']);
Route::post('/posts', [PostController::class, 'store']);
Route::delete('/posts/{post}', [PostController::class, 'destroy']);

// Likes...
Route::get('/posts/{post}/likes', [LikeController::class, 'index']);
Route::post('/posts/{post}/likes', [LikeController::class, 'store']);
Route::delete('/posts/{post}/likes', [LikeController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
