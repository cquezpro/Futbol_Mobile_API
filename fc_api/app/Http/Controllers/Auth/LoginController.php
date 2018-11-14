<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /**
     * Login Controller
     * Este controlador maneja la autenticación de usuarios para la aplicación y
     * redirigiéndolos a su pantalla de inicio. El controlador usa un rasgo
     * para proporcionar convenientemente su funcionalidad a sus aplicaciones.
     * 
     * @author Kevin Cifuentes
     */
   
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }
}
