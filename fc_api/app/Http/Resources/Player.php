<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Player extends JsonResource
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
            "descripcion"   => $this->descripcion,
            "player_number" => $this->player_number,
            "futbol_sports" => $this->futbol_sports,
            "pie_fuerte"    => $this->pie_fuerte,
            "pie_debil"     => $this->pie_debil,
            "altura"        => $this->altura,
            "peso"          => $this->peso,
            "fut_types"     => $this->user->futboltypes,
            "game_pos"      => $this->user->gamepositions,
            "created_at"    => ucfirst($this->created_at->toFormattedDateString()),
            "updated_at"    => ucfirst($this->updated_at->toFormattedDateString()),
            "user"          => new User($this->user),
        ];
    }
}
