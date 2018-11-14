<?php

namespace App\Events;

use App\Http\Resources\User as UserResource;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Pusher\PushNotifications\PushNotifications;

/**
 * Notifica al usuario cuando tiene un nuevo seguidor.
 * @access public 
 * @author @Kevin Cifuentes
 */
class FollowerEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    /**
     * @var User
     */
    public $user;
    /**
     * @var User
     */
    public $senderUser;
    /**
     * @var string
     */
    public $type;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param User $senderUser
     * @param string $type
     */
    public function __construct(User $user, User $senderUser, $type = 'follow')
    {
        //
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
        return new PrivateChannel('App.User.' . $this->user->hash_id);
    }

    public function broadcastAs()
    {
        if ($this->type === 'follow') {
            return 'users.follow';
        } elseif ($this->type === 'unfollow') {
            return 'users.unfollow';
        }
    }

    /**
     * @access public
     * title => título de la notificación,
     * body  => indica el usuario que ha realizado el comentario
     * @return retorna una notificación, cuando se ha empezado a seguir a un usuario
     */
    public function broadcastWith()
    {
        $avatar_path = ($this->senderUser->avatar) ? $this->senderUser->avatar->avatar_path : null;

        $resp = [
            'sender_user' => [
                'full_name' => $this->senderUser->full_name,
                'hash_id'   => $this->user->hash_id,
                'avatar'    => $avatar_path,
            ],

        ];

        try {
            $pushNotifications = new PushNotifications([
                "instanceId" => "bc9bfcbd-f995-40c1-a691-59ae68d08dd5",
                "secretKey"  => "172A667849485EB4B100FD8F6CE320B",
            ]);


            $type = null;
            if ($this->type === 'follow') {
                $type = ["User.Follow." . $this->user->hash_id];
            } elseif ($this->type === 'unfollow') {
                $type = ["User.Unfollow." . $this->user->hash_id];
            }
            $publishResponse = $pushNotifications->publish(
                $type,
                [
                    "fcm"  => [
                        "notification" => [
                            's'     => 1,
                            'e'     => 1,
                            'title' => __('app.follow_notification.title'),
                            'body'  => $this->senderUser->full_name . __('app.notification_follow.follow_body'),
                            //'body'  => "El usuario " . $this->senderUser->full_name . " ahora te sigue.",
                        ],
                        "data"         => [
                            "type" => $this->type,
                            "user" => $this->senderUser->hash_id,
                        ],
                    ],
                    "apns" => [
                        "aps" => [
                            "alert" => [
                                's'     => 1,
                                'e'     => 1,
                                'title' => __('app.follow_notification.title'),
                                'body'  => $this->senderUser->full_name . __('app.notification_follow.follow_body'),
                                //'body'  => "El usuario " . $this->senderUser->full_name . " ahora te sigue.",
                            ],
                            "data"  => [
                                "type" => $this->type,
                            ],
                        ],
                    ],
                ]
            );
        } catch (\Exception $e) {
            abort(500, $e);
        }

        return $resp;
    }
}
