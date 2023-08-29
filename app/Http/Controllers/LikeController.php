<?php

namespace App\Http\Controllers;

use App\Http\Resources\LikeResource;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends ApiController
{
    public function updateLike(Request $request) {
        $post = Post::where('slug', $request->slug)->first();
        $like = Like::query()->firstOrCreate([
            'user_id' => auth()->user()->id,
            'likeable_id' => $post->id,
            'likeable_type' => 'post'
        ]);
        $like->update(['is_liked' => !$like->is_liked]);
        return $this->successResponse('like model is updated', 200, new LikeResource($like));
    }
}
