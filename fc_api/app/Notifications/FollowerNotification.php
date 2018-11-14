<?php

namespace App\Notifications;

use App\Http\Resources\User as UserResource;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class FollowerNotification extends Notification
{
    use Queueable;
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
     * Create a new notification instance.
     *
     * @param User $user
     * @param User $senderUser
     * @param string $type
     */
    public function __construct(User $user, User $senderUser, $type = 'follower')
    {
        //
        $this->user = new UserResource($user);
        $this->senderUser = new UserResource($senderUser);
        $this->type = $type;
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
            "type"       => $this->type,
        ];
    }
}
