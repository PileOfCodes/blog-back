<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\CategoryPost;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PostController extends ApiController
{
    public function index()
    {
        $posts = Post::paginate(8);
        return $this->successResponse('all posts', 200, [
            'posts' => PostResource::collection($posts),
            'links' => PostResource::collection($posts)->response()->getData()->links,
            'meta' => PostResource::collection($posts)->response()->getData()->meta,
        ]);
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'englishTitle' => 'required',
            'persianTitle' => 'required',
            'englishBody' => 'required',
            'persianBody' => 'required',
            'category_id' => 'required',
            'eventTime' => 'nullable',
            'image' => 'required|image',
            'categories.*' => 'required'
        ]);
        if($validate->fails()) {
            return $this->errorResponse($validate->messages(), 422);
        }
        if($request->has('image')) {
            $imageName = Carbon::now()->microsecond . '.' . $request->image->extension();
            $request->image->storeAs('images/posts', $imageName, 'public' ); 
        }
        DB::beginTransaction();
        $post = Post::create([
            'englishTitle' => $request->englishTitle,
            'persianTitle' => $request->persianTitle,
            'eventTime' => $request->has('eventTime') ? $request->eventTime : null,
            'persianBody' => $request->persianBody,
            'englishBody' => $request->englishBody,
            'image' => $imageName,
            'user_id' => 1,
            'category_id' => $request->category_id
        ]);
        if($request->has('categories')) {
            foreach ($request->categories as $category) {
                CategoryPost::create([
                    'post_id' => $post->id,
                    'category_id' => $category['category_id']
                ]);
            }
        }
        DB::commit();
        return $this->errorResponse('post created successfully', 201, new PostResource($post));
    }

    public function show(Post $post)
    {
        
    }

    public function update(Request $request, Post $post)
    {
        // $validate = Validator::make($request->all(), [
        //     'englishTitle' => 'required',
        //     'persianTitle' => 'required',
        //     'englishBody' => 'nullable',
        //     'persianBody' => 'nullable',
        //     'category_id' => 'required',
        //     'eventTime' => 'nullable',
        //     'image' => 'nullable|image'
        // ]);
        // if($validate->fails()) {
        //     return $this->errorResponse($validate->messages(), 422);
        // }
        if($request->has('image')) {
            $imageName = Carbon::now()->microsecond . '.' . $request->image->extension();
            $request->image->storeAs('images/posts', $imageName, 'public' ); 
        }
        DB::beginTransaction();
        $post->update([
            'englishTitle' => $request->has('englishTitle') ? $request->englishTitle : $post->englishTitle,
            'persianTitle' => $request->has('persianTitle') ? $request->persianTitle : $post->persianTitle,
            'eventTime' => $request->has('eventTime') ? $request->eventTime : $post->eventTime,
            'persianBody' => $request->has('persianBody') ? $request->persianBody : $post->persianBody,
            'englishBody' => $request->has('englishBody') ? $request->englishBody : $post->englishBody,
            'image' => $request->has('image') ? $imageName : $post->image,
            'user_id' => 1
        ]);
        DB::commit();
        return $this->errorResponse('post updated successfully', 200, new PostResource($post));
    }

    public function destroy(Post $post)
    {
        //
    }
}
