<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\CategoryPost;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PostController extends ApiController
{
    public function allPosts(Request $request) {
        if($request->search != '' || $request->search != null) {
            $posts = Post::where('englishTitle', 'LIKE', "%{$request->search}%")
            ->orWhere('persianTitle', 'LIKE', "%{$request->search}%")
            ->orderBy('created_at', 'desc')->paginate(2);
            return $this->successResponse('all posts', 200, [
                'posts' => PostResource::collection($posts),
                'links' => PostResource::collection($posts)->response()->getData()->links,
                'meta' => PostResource::collection($posts)->response()->getData()->meta
            ]);
        }else {
            $posts = Post::orderBy('created_at', 'desc')->paginate(2);
            return $this->successResponse('all posts', 200, [
                'posts' => PostResource::collection($posts),
                'links' => PostResource::collection($posts)->response()->getData()->links,
                'meta' => PostResource::collection($posts)->response()->getData()->meta
            ]);
        }
    }

    public function getPost(Request $request) {
        $post = Post::where('slug', $request->slug)->first();
        return $this->successResponse('get post', 200, new PostResource($post));
    }

    public function relatedPosts(Request $request) {
        $post = Post::where('slug', $request->slug)->first();
        $categoryIds = CategoryPost::where('post_id', $post->id)->pluck('category_id');
        $postIds = CategoryPost::whereIn('category_id', $categoryIds)->pluck('post_id');
        $posts = Post::where('slug', '!=', $request->slug)
        ->whereIn('id', $postIds)->orderBy('created_at','desc')->take(4)->get();
        return $this->successResponse('related posts', 200, PostResource::collection($posts));
    }
}
