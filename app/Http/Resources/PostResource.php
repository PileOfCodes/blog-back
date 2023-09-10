<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Models\CategoryPost;
use App\Models\Comment;
use App\Models\Like;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = User::where('id', $this->user_id)->first();
        $categoryIds = CategoryPost::where('post_id', $this->id)->pluck('category_id');
        $categories = Category::whereIn('id', $categoryIds)->get();
        $likes = Like::where('likeable_type', 'post')->where('is_liked', 1)
        ->where('likeable_id', $this->id)->get();
        $comments = Comment::where('post_id', $this->id)->where('status', 1)->get(); 
        return [
            'id' => $this->id,
            'englishTitle' => $this->englishTitle,
            'persianTitle' => $this->persianTitle,
            'persianBody' => $this->persianBody,
            'englishBody' => $this->englishBody,
            'categories' => CategoryResource::collection($categories),
            'englishDate' => Carbon::parse($this->created_at)->isoFormat('MMMM Do YYYY'),
            'persianDate' => verta($this->created_at)->format('%B %d، %Y'),
            'image' => url(env('POST_IMAGE') . $this->image),
            'userImage' => url(env('USER_IMAGE') . $user->image),
            'userName' => $user->name,
            'slug' => $this->slug,
            'owner' => $user->name,
            'likes_count' => count($likes),
            'comments_count' => count($comments)
        ];
    }
}
