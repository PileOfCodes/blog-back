<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArchiveResource;
use App\Http\Resources\PostResource;
use App\Models\Archive;
use App\Models\ArchivePost;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;

class LandingController extends ApiController
{
    public function mostFavorite()
    {
        $likes_count = collect([]);
        $collected_posts = collect([]);
        $posts = Post::pluck('id');
        foreach ($posts as $postId) {
            $likes = Like::where('is_liked', 1)->where('likeable_id',$postId)
            ->where('likeable_type', 'post')->get();
            $likes_count->push([
                'post_id' => $postId,
                'likes_count' => count($likes)
            ]);
        }
        foreach($likes_count->sortByDesc('likes_count')->slice(0,4) as $liked) {
            $post = Post::where('id', $liked['post_id'])->first();
            $collected_posts->push($post);
        }
        return $this->successResponse('most favorite posts', 200, PostResource::collection($collected_posts));
    }

    public function mostVisit() {
        $posts = Post::orderBy('visit', 'desc')->take(4)->get();
        return $this->successResponse('most visit articles', 200, PostResource::collection($posts));
    }

    public function newest() {
        $posts = Post::orderBy('created_at', 'desc')->take(4)->get();
        return $this->successResponse('most visit articles', 200, PostResource::collection($posts));
    }

    public function getArchiveDates() {
        $dates = Archive::all();
        return $this->successResponse('archive', 200, ArchiveResource::collection($dates));
    }
}
