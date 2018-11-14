<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /**
     * Password Reset Controller
     * Este controlador es responsable de manejar las solicitudes de restablecimiento de contraseña
     * y usa un rasgo simple para incluir este comportamiento. Eres libre de
     * explora este rasgo y anula cualquier método que se  desee modificar.
     * 
     * @author Kevin Cifuentes
     */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

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
