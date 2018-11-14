<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Matches extends JsonResource
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
            "game_id" => $this->game_id,
            "status" => $this->status,
            "localteam_id" => $this->localteam_id,
            "localteam_name" => $this->localteam_name,
            "localteam_patch" => $this->localteam_patch,
            "local_statistics" => json_decode(json_encode($this->local_statistics)),
            "visitorteam_id" => $this->visitorteam_id,
            "visitorteam_name" => $this->visitorteam_name,
            "visitorteam_patch" => $this->visitorteam_patch,
            "visitor_statistics" => json_decode($this->visitor_statistics),
        ];
    }
}
