<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Front\CategoryController as FrontCategoryController;
use App\Http\Controllers\Front\PostController as FrontPostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/post/updateLike', [LikeController::class, 'updateLike']);
});

Route::prefix('admin')->middleware('auth:sanctum')->group(function() {
    Route::apiResource('posts', PostController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('comments', CommentController::class);
});
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/getPosts', [FrontPostController::class, 'allPosts']);
Route::get('/landing/getCategories', [FrontCategoryController::class, 'allCategories']);
Route::get('/landing/getSliders', [SliderController::class, 'allSliders']);
Route::get('/comment/articleComments', [CommentController::class, 'getComments']);
Route::get('/comment/getRepliesLength', [CommentController::class, 'getRepliesLength']);
Route::get('/comment/articleAllComments', [CommentController::class, 'articleAllComments']);
// post
Route::get('/posts/getPost', [FrontPostController::class, 'getPost']);
