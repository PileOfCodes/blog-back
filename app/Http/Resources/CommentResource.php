<?php

namespace App\Http\Resources;

use App\Models\Comment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = User::where('id', $this->user_id)->first();
        $comments = Comment::where('comment_id', $this->id)->where('status', 1)->get();
        return [
            'id' => $this->id,
            'user' => new UserResource($user),
            'body' => $this->body,
            'replies' => CommentResource::collection($comments),
            'persiandate' => verta($this->created_at)->formatDifference(),
            'englishdate' => $this->created_at->diffForHumans()
        ];
    }
}
