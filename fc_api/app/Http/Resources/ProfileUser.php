<?php

namespace App\Http\Resources;

use App\Helpers\Helpers;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileUser extends JsonResource
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
            "user_id"      => $this->user_id,   
            "profile_id"   => $this->profile_id,
            "status"       => $this->status,
            "created_at"   => ucfirst($this->created_at->toFormattedDateString()),
            "updated_at"   => ucfirst($this->updated_at->toFormattedDateString()),
            "user"         => new User($this->user),
        ];
    }
}