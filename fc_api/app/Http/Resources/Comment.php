<?php

namespace App\Http\Resources;

use App\Helpers\Helpers;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class Comment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $isOwner = $request->user()->id == $this->user->id ? true : false;
        return [
            "id"              => $this->id,
            "body"            => $this->body,
            "created_at"      => ucfirst($this->created_at->toFormattedDateString()),
            "updated_at"      => ucfirst($this->updated_at->toFormattedDateString()),
            "created_at_diff" => Helpers::decHumanDiffDate($this->created_at),
            "updated_at_diff" => Helpers::decHumanDiffDate($this->updated_at),
            "isOwner"         => $isOwner,
            "link_edit"       => route('api.posts.comments.update', $this->id),
            "user"            => [
                'full_name' => $this->user->full_name,
                'avatar'    => $this->user->avatar()->activeAvatar()->first(),
                "links"     => [
                    "show_user" => route('api.users.show', $this->user->id),
                ],
            ],
        ];
    }
}
