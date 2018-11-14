<?php

namespace App\Events;

use App\Http\Resources\Post as PostResource;
use App\Http\Resources\User as UserResource;
use App\Post;
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
 * Envía una notificación cuando a un usuario le gusta una publicación.  Las publicaciones pueden ser imágenes y/o videos acompañadas con texto.
 * @access public
 * @author @Kevin Cifuentes
 */
class LikesNotifications implements ShouldBroadcast
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
     * @var Post
     */
    public $post;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param User $senderUser
     * @param Post $post
     */
    public function __construct(User $user, User $senderUser, Post $post)
    {

        $this->user = new UserResource($user);
        $this->senderUser = new UserResource($senderUser);
        $this->post = new PostResource($post);
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
        return 'post.likes';
    }

    /**
     * Método que se encarga de notificar cuando un usuario indica 'like' a una publicación.
     * title     => título de la notificación,
     * body      => indica el usuario que ha realizado el comentario
     * post_show => indica la ruta desde donde se realizó la acción de Like
     * @access public 
     */
    public function broadcastWith()
    {
        $avatar_path = ($this->senderUser->avatar) ? $this->senderUser->avatar->avatar_path : null;

        $resp = [
            'sender_user' => [
                'full_name' => $this->senderUser->full_name,
                'hash_id'   => $this->user->id,
                'avatar'    => $avatar_path,
            ],
            'post'        => [
                'post_body'    => $this->post->body,
                'post_hash_id' => $this->post->id,
            ]

        ];

        /*try {
            $pushNotifications = new PushNotifications([
                "instanceId" => "bc9bfcbd-f995-40c1-a691-59ae68d08dd5",
                "secretKey"  => "172A667849485EB4B100FD8F6CE320B",
            ]);

            $publishResponse = $pushNotifications->publish(
                ["Post.Likes." . $this->user->hash_id],
                [
                    "fcm"  => [
                        "notification" => [
                            's'     => 1,
                            'e'     => 1,
                            'title' => __('app.like_notification.title'),
                            'body'  => $this->senderUser->full_name . __('app.like_notification.like_body') . str_limit($this->post->body, 15),
                            //'body'  => "El usuario " . $this->senderUser->full_name . "le gustó tu publicación" . str_limit($this->post->body, 15),
                        ],
                        "data"         => [
                            "type" => "like",
                            'post' => [
                                'post_body'    => $this->post->body,
                                'post_hash_id' => $this->post->hash_id,
                            ],
                            "user" => $this->post->user->hash_id,
                        ],
                    ],
                    "apns" => [
                        "aps" => [
                            "alert" => [
                                "title" => __('app.coment_notification.title_push'),
                                "body"  => __('app.coment_notification.push'),
                            ],
                        ],
                    ],
                ]
            );
        } catch (\Exception $e) {
            abort(500, $e);
        }*/

        return $resp;
    }
}
