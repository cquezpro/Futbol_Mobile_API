<?php

namespace App\Notifications;

use App\Comment;
use App\Http\Resources\Comment as CommentResource;
use App\Http\Resources\User as UserResource;
use App\Post;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CommentPostNotification extends Notification
{
    use Queueable;
    public $senderUser;
    public $post;
    /**
     * @var Comment
     */
    private $comment;

    /**
     * Create a new notification instance.
     *
     * @param Post $post
     * @param User $senderUser
     * @param Comment $comment
     */
    public function __construct(Post $post, User $senderUser, Comment $comment)
    {
        $this->post = $post;
        $this->senderUser = new UserResource($senderUser);
        $this->comment = new CommentResource($comment);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            "userSender" => $this->senderUser,
            "post"       => $this->post,
            "comment"    => $this->comment,
        ];
    }

    /*
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed $notifiable
     * @return BroadcastMessage
     */
    /*public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            "userSender" => $this->senderUser,
            "post"       => $this->post,
            "comment"    => $this->comment,
        ]);
    }*/

    public function broadcastAs()
    {
        return 'post.comment';
    }
}
