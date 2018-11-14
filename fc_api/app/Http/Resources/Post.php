<?php

namespace App\Http\Resources;

use App\Helpers\Helpers;
use Illuminate\Http\Resources\Json\JsonResource;

class Post extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $iLiked = false;
        foreach ($this->likes as $like) {
            if ($like->user_id == $request->user()->id) {
                $iLiked = true;
                break;
            }
        }

        return [
            'body' => $this->body,
            'id' => $this->id,
            "created_at" => ucfirst($this->created_at->toFormattedDateString()),
            "updated_at" => ucfirst($this->updated_at->toFormattedDateString()),
            "created_at_diff" => Helpers::decHumanDiffDate($this->created_at),
            "updated_at_diff" => Helpers::decHumanDiffDate($this->updated_at),
            'likes' => [
                'total' => $this->likes->count(),
                'iLiked' => $iLiked,
            ],
            'images' => new PostImageCollection($this->postImages),
            'videos' => new PostVideoCollection($this->postVideos),
            'user' => new User($this->user),
            'total_comments' => count($this->comments),
            'comments' => new CommentCollection($this->comments()->orderBy('created_at', 'desc')->get()),
            'link' => [
                'post' => route('api.posts.update', $this->id),
            ],
        ];
    }
}
