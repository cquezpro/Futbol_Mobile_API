<?php

namespace App\Http\Resources;

use App\Helpers\Helpers;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerGame extends JsonResource
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
            "id"            => $this->id,
            "game_position" => new GamePosition($this->game_positions_id),
            "created_at"    => ucfirst($this->created_at->toFormattedDateString()),
            "updated_at"    => ucfirst($this->updated_at->toFormattedDateString()),
            'player'        => new Player($this->players_id),
            'link'            => [
                'player' => route('api.playergame.show', $this->id),
            ],
        ];
    }
}
