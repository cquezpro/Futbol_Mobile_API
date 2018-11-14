<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /**
     * Password Reset Controller
     * Este controlador es responsable de manejar correos electrónicos de restablecimiento de contraseña y
     * incluye un rasgo que ayuda a enviar estas notificaciones desde
     * su aplicación a sus usuarios. Siéntase libre de explorar este rasgo.
     * 
     * @author @Kevin Cifuente
     */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
}
