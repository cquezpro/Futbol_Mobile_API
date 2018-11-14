<?php

namespace App\Events;

use App\Comment;
use App\Http\Resources\Comment as CommentResource;
use App\Http\Resources\Post as PostResoruce;
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
 * Notificación de comentarios a una publicación.
 * 
 * @access public
 * @author @Kevin Cifuentes
 */
class CommentNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user;
    /**
     * @var Post
     */
    public $post;
    /**
     * @var User
     */
    private $senderUser;
    /**
     * @access private
     * @var Comment
     */
    private $comment;

    /**
     * Create a new event instance.
     *
     * @param User $user
     * @param Post $post
     * @param User $senderUser
     * @param Comment $comment
     */
    public function __construct(User $user, Post $post, User $senderUser, Comment $comment)
    {
        $this->user = new UserResource($user);
        $this->post = new PostResoruce($post);
        $this->senderUser = new UserResource($senderUser);
        $this->comment = new CommentResource($comment);
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
        return 'post.comments';
    }

    /**
     * Método que se encarga de enviar notificaciones cuando un usuario ha comentado un post
     * @access public
     * 
     * title => título de la notificación,
     * body  => indica el usuario que ha realizado el comentario
     * post_show => indica la ruta desde donde se realizó el comentario
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
            'post'        => [
                'post_body'    => $this->post->body,
                'post_hash_id' => $this->post->hash_id,
            ],
            'links'       => [
                'post_show' => route('api.posts.show', $this->post->id),
            ],
        ];
        try {
            $pushNotifications = new PushNotifications([
                "instanceId" => "bc9bfcbd-f995-40c1-a691-59ae68d08dd5",
                "secretKey"  => "172A667849485EB4B100FD8F6CE320B",
            ]);

            $publishResponse = $pushNotifications->publish(
                ["Post.Comments." . $this->user->id],
                [
                    "fcm"  => [
                        "notification" => [
                            's'     => 1,
                            'e'     => 1,
                            'title' => __('app.coment_notification.title'),
                            'body'  => $this->senderUser->full_name . __('app.coment_notification.new_comment'). str_limit($this->post->body, 15),
                            //'body'  => "El usuario " . $this->senderUser->full_name . " ha comentado tu post  " . str_limit($this->post->body, 15),
                        ],
                        "data"         => [
                            "type" => "comment",
                            "post" => [
                                'title' => $this->post->title,
                                'body'  => $this->post->body,
                            ],
                            "user" => $this->user->id,
                        ],
                    ],
                    "apns" => [
                        "aps" => [
                            "alert" => [
                                "title" => __('app.coment_notification.title_push'),
                                "body"  => __('app.coment_notification.push'),
                                //"body"  => "This is my first Push Notification!",
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
