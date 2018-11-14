<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\NexmoMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserSendPhoneCode extends Notification
{
    use Queueable;
    private $code;
    private $message;

    /**
     * Create a new message instance.
     * Envía mensaje de texto con el código de verificación y/o nueva contraseña dependiendo del parámetro que reciba la función
     * 
     * @param $code código de 4 dígitos
     * @param string $type por defecto 'register'
     * @param null $newPsw
     */
    public function __construct($code, $type = 'register')
    {
        switch ($type) {
            case 'register':
                $this->message = __('app.notification.register_phn',['code' => $code]);
                break;
            case 'reset_psw':
                $this->message = __('app.notification.reset_psw_phn',['code' => $code]);
                break;
            case 'send_new_psw':
                $this->message = __('app.notification.send_new_psw_phn',['code' => $code]);
                break;
            case 'update_mail_phone':
                $this->message = __('app.notification.update_mailphone',['code' => $code]);
                break;
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['nexmo'];
    }

    public function toNexmo($notifiable)
    {
        return (new NexmoMessage())
            ->content($this->message)
            ->unicode();
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
            //
        ];
    }
}
