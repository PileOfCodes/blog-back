<?php

namespace App\Http\Resources;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LikeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $post = Post::where('id', $this->likeable_id)->first();
        return [
            'user_id' => $this->user_id,
            'post' => new PostResource($post),
            'isLiked' => $this->is_liked
        ];
    }
}
