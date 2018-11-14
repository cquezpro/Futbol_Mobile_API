<?php

namespace App\Notifications;

use App\Http\Resources\User as UserResource;
use App\Post;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Pusher\PushNotifications\PushNotifications;

class LikePostNotification extends Notification
{
    use Queueable;

    public $senderUser;
    public $post;

    /**
     * Create a new notification instance.
     *
     * @param Post $post
     * @param User $senderUser
     */
    public function __construct(Post $post, User $senderUser)
    {
        $this->post = $post;
        $this->senderUser = new UserResource($senderUser);
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
        $resp = [
            "userSender" => $this->senderUser,
            "post"       => $this->post,
        ];

        return $resp;
    }
}
