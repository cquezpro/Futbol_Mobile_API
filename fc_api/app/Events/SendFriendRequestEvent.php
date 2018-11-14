<?php

namespace App\Events;

use App\Http\Resources\User as UserResource;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendFriendRequestEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user;
    public $senderUser;
    public $type;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, User $senderUser, $type = "send")
    {
        $this->user = new UserResource($user);
        $this->senderUser = new UserResource($senderUser);
        $this->type = $type;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.User.' . $this->user->id);
    }

    public function broadcastAs()
    {
        return 'friend.request';
    }

    public function broadcastWith()
    {
        $resp = [
            "type" => $this->type,
            'senderUser' => [
                'name' => $this->senderUser->full_name,
                'avatar' => $this->senderUser->avatar()->activeAvatar()->first(),
            ],
        ];
        return $resp;
    }
}
