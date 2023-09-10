<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\CategoryPost;
use App\Models\Post;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    public function allCategories() {
        $categories = Category::take(11)->get();
        return $this->successResponse('all categories', 200, CategoryResource::collection($categories));
    }

    public function index() {
        $categories = Category::all();
        return $this->successResponse('get all categories', 200, CategoryResource::collection($categories));
    }

    public function getCategory(Request $request) {
        $category = Category::where('slug', $request->slug)->first();
        return $this->successResponse('get all categories', 200, new CategoryResource($category));
    }

    public function getArticles(Request $request) {
        $category = Category::where('slug', $request->slug)->first();
        $postIds = CategoryPost::where('category_id', $category->id)->pluck('post_id');
        $posts = Post::whereIn('id', $postIds)->get();
        return $this->successResponse('get all category articles', 200, PostResource::collection($posts));
    }

    public function relatedArticles(Request $request) {
        $post = Post::where('slug', $request->slug)->first();
        $categoryIds = CategoryPost::where('post_id', $post->id)->pluck('category_id');
        $postIds = CategoryPost::whereIn('category_id', $categoryIds)->pluck('post_id');
        $posts = Post::whereIn('id', $postIds)->where('id', '!=', $post->id)->get();
        return $this->successResponse('get all related articles', 200, PostResource::collection($posts));
    }
}
