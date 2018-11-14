<?php

namespace App\Http\Resources;

use App\Helpers\Helpers;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Jenssegers\Date\Date;

class ChatMessage extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $localUser = $request->user();
        $isSender = $this->from->id === $localUser->id;

        $created_at = new Date($this->created_at);
        $updated_at = new Date($this->updated_at);

        $user = User::find($this->from->id);

        return [
            'id' => $this->id,
            'from' => [
                'full_name' => $user->full_name,
                'avatar' => $user->avatar()->activeAvatar()->first(),
                'id' => $user->id,
            ],
            'body' => $this->body,
            'isRead' => !!$this->is_read,
            'isSender' => $isSender,
            "created_at_diff" => Helpers::decHumanDiffDate($created_at),
            "updated_at_diff" => Helpers::decHumanDiffDate($updated_at),
        ];
    }
}
