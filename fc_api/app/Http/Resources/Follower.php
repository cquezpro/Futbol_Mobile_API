<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Follower extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $user = new User($this);
        return [
            'full_name' => $user->full_name,
            'avatar'    => $user->avatar,
            'hash_id'   => $user->hash_id,
        ];
    }
}
