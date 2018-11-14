<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserValidationCode extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $description;
    public $pswOrCode;

    /**
     * Create a new message instance.
     * Envía mensaje al email con el código de verificación y/o nueva contraseña dependiendo del parámetro que reciba la función
     * @param User $user
     * @param string $type por defecto 'register' 
     * @param null $newPsw
     */
    public function __construct(User $user, $type = 'register', $newPsw = null)
    {
        $this->user = $user;
        $this->pswOrCode = $user->confirmed_code;
        switch ($type) {
            case 'register':
                $this->description = __('app.notification.register');
                break;
            case 'reset_psw':
                $this->description = __('app.notification.reset_psw');
                break;
            case 'send_new_psw':
                $this->description = __('app.notification.send_new_psw');
                $this->pswOrCode = $newPsw;
                break;
            case 'update_mail_phone':
                $this->description = __('app.notification.update_mail_phone');
                break;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.users.validation_code');
    }
}
