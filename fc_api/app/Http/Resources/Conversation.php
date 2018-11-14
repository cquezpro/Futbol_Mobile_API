<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Conversation extends JsonResource
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
        $users = new UserCollection($this->users()->where('user_id', '<>',$localUser->id)->get());

        $lastMessage = $this->messages()->orderBy('created_at', 'desc')->first();

        return [
            'id'                => $this->id,
            'users'             => $users,
            'last_message'      => new ChatMessage($lastMessage),
        ];
    }
}
