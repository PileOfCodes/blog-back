<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Front\CategoryController as FrontCategoryController;
use App\Http\Controllers\Front\PostController as FrontPostController;
use App\Http\Controllers\LandingController;
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
    Route::post('/auth/profile', [AuthController::class, 'changeProfile']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/post/updateLike', [LikeController::class, 'updateLike']);
    Route::post('/post/comment/updateLike', [LikeController::class, 'updateCommentLike']);
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
Route::get('/landing/mostFavorite',[LandingController::class, 'mostFavorite']);
Route::get('/landing/mostVisit',[LandingController::class, 'mostVisit']);
Route::get('/landing/newest',[LandingController::class, 'newest']);
Route::get('/landing/archive',[LandingController::class, 'getArchiveDates']);
Route::get('/comment/articleComments', [CommentController::class, 'getComments']);
Route::get('/comment/getRepliesLength', [CommentController::class, 'getRepliesLength']);
Route::get('/comment/articleAllComments', [CommentController::class, 'articleAllComments']);
// post
Route::get('/posts/getPost', [FrontPostController::class, 'getPost']);
Route::get('/posts/getVisit', [FrontPostController::class, 'getVisit']);
Route::get('/posts/archive', [FrontPostController::class, 'getArchivePosts']);
Route::get('/post/relatedPosts', [FrontPostController::class, 'relatedPosts']);
// categories
Route::get('/category/index', [FrontCategoryController::class, 'index']);
Route::get('/category/getCategory', [FrontCategoryController::class, 'getCategory']);
Route::get('/category/relatedArticles', [FrontCategoryController::class, 'relatedArticles']);
Route::get('/category/getArticles', [FrontCategoryController::class, 'getArticles']);
