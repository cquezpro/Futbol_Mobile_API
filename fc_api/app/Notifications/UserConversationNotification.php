<?php

namespace App\Notifications;

use App\ChatMessage;
use App\Conversation;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class UserConversationNotification extends Notification
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
     * @var Conversation
     */
    public $conversation;
    /**
     * @var ChatMessage
     */
    public $message;

    /**
     * Create a new notification instance.
     *
     * @param User $user
     * @param User $senderUser
     * @param Conversation $conversation
     * @param ChatMessage $message
     */
    public function __construct(User $user, Conversation $conversation, $message)
    {
        $this->user = $user;
        $this->conversation = $conversation;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', OneSignalChannel::class];
    }

    public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
            ->subject("{$this->user->full_name} Te ha enviado un mensaje.")
            ->body("Me {$this->message}")
            ->url('http://onesignal.com')
            ->webButton(
                OneSignalWebButton::create('link-1')
                    ->text('Click here')
                    ->icon('https://upload.wikimedia.org/wikipedia/commons/4/4f/Laravel_logo.png')
                    ->url('http://laravel.com')
            );
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
            "user" => $this->user,
            "conversation" => $this->conversation,
            "message" => $this->message,
        ];
    }
}
