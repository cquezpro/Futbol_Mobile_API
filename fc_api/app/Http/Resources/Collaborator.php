<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Collaborator extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            "description"        => $this->description,
            "city"               => $this->city,
            "link_linkedin"      => $this->link_linkedin,
            "link_blog"          => $this->link_blog,
            "link_facebook"      => $this->link_facebook,
            "link_youtube"       => $this->link_youtube,
            "link_instagram"     => $this->link_instagram,
            "fut_types"          => $this->user->futboltypes,
            "collaborator_type"  => $this->collaborator_type,
            "user"               => new User($this->user),
        ];
    }
}
