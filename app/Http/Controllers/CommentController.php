<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CommentController extends ApiController
{
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'body' => 'required',
            'post_id' => 'required'
        ]);
        if($validate->fails()) {
            return $this->errorResponse($validate->messages(), 422);
        }
        DB::beginTransaction();
        $comment = Comment::create([
            'user_id' => auth()->user()->id,
            'post_id' => $request->post_id,
            'comment_id' => $request->has('comment_id') ? $request->comment_id : null,
            'body' => $request->body
        ]);
        DB::commit();
        return $this->successResponse('comment created successfully', 201, new CommentResource($comment));
    }

    public function show(Comment $comment)
    {
        return $this->successResponse("comment {$comment->id}", 200, new CommentResource($comment));
    }

    public function update(Request $request, Comment $comment)
    {
        //
    }

    public function destroy(Comment $comment)
    {
        //
    }

    public function getComments(Request $request) {
        $post = Post::where('slug',$request->slug)->first();
        $comments = Comment::where('post_id', $post->id)->where('comment_id', null)->where('status', 1)->get();
        return $this->successResponse("article comments", 200, CommentResource::collection($comments));
    }

    public function getRepliesLength(Request $request) {
        $post = Post::where('slug',$request->slug)->first();
        $comments = Comment::where('post_id', $post->id)
        ->where('comment_id', '!=' , null)->where('status', 1)->get();
        return $this->successResponse("all reply comments length", 200, ['length' => count($comments)]);
    }

    public function articleAllComments(Request $request) {
        $post = Post::where('slug',$request->slug)->first();
        $comments = Comment::where('post_id', $post->id)->where('status', 1)->get();
        return $this->successResponse("all comments", 200,['length' => count($comments)]);
    }

    
}
