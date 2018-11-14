<?php

namespace App\Http\Resources;

use App\Helpers\Helpers;
use Illuminate\Http\Resources\Json\JsonResource;

class Coach extends JsonResource
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
            "description"     => $this->description,
            "created_at"      => ucfirst($this->created_at->toFormattedDateString()),
            "updated_at"      => ucfirst($this->updated_at->toFormattedDateString()),
            'user_id'         => new User($this->user),
        ];
    }
}
