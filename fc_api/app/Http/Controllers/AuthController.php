<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\User as UserResouce;
use App\Mail\UserValidationCode;
use App\Notifications\UserSendPhoneCode;
use App\ProfileUser;
use App\Team;
use App\User;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Pusher\Pusher;

/**
 * <b>Name=</b> Auth<br>
 * <b>Description=</b> Metodos para el registro, autenticación de usuario y recuperación de contraseña.
 *
 */
class AuthController extends Controller
{
    use ThrottlesLogins;

    /**
     *
     * Método para registrar un usuario.
     * Email y teléfono deben ser únicos en la DB, se debe enviar código de verificación por email y phone.
     * Es requrido el nombre y apellido con mínimo 3 caracteres.
     *
     * @access public
     *
     * @param StoreUserRequest $request datos personales del usuario.
     * @author Kevin Cifuentes
     *
     * @return $user
     */
    public function store(StoreUserRequest $request)
    {
        $token = null;
        $user = null;
        if ($request->has('provider')) {
            $user = User::where('provider_id', $request->provider_id)->first();
        }

        if (!$user) {
            Log::info($request->all());
            $request->validate([
                'first_name' => 'required|min:3',
                'last_name' => 'required|min:3',
                //'password' => 'required',
            ]);

            $user = new User([
                "first_name" => strtolower($request->first_name),
                "last_name" => strtolower($request->last_name),
                "provider_id" => $request->provider_id,
                "provider" => $request->provider,
            ]);

            if (!$request->has('provider_id')) { //comienza validador
                $sw = '';
                if ($request->has('email') || $request->has('phone')) {
                    if ($request->has('email')) {
                        $isValid = Helpers::isValidEmail(strtolower($request->email));
                        if (!$isValid) {
                            return response()->json([
                                'message' => 'Verifique el formato de correo sea el correcto.',
                                'token' => '',
                                'user' => '',
                                'isNotConfirmed' => true,
                            ], 422);
                        } else {
                            $request->validate([
                                'email' => 'unique:users',
                            ]);
                        }
                        $sw = 1;
                    } else {
                        $request->validate([
                            'phone' => 'numeric',
                        ]);
                        $sw = 0;
                    }

                    //if($sw==0){
                    $user->phone_code = str_replace('+', '', $request->phone_code);
                    $user->phone = $request->phone;
                    //}else{
                    $user->email = strtolower($request->email);
                    //}
                    $user->password = Hash::make($request->password);
                    $user->confirmed_code = Helpers::genCode(4);

                    $user->save();

                    $fan = new ProfileUser([
                        'user_id' => $user->id,
                        'profile_id' => 1,
                        'status' => 1,
                    ]);
                    $fan->save();

                    if ($isValid) {
                        Log::info("entró en el mail");
                        Mail::to($user)->send(new UserValidationCode($user));
                    }

                    if ($request->has('phone') && $request->phone) {
                        $user->notify(new UserSendPhoneCode($user->confirmed_code));
                    }

                    return response()->json([
                        'message' => 'Usuario registrado con éxito.',
                        'token' => '',
                        'user' => new UserResouce($user),
                        'isNotConfirmed' => true,
                    ], 200);
                } else {
                    return response()->json([
                        'message' => 'Este tipo de registro requiere que se envíe el e-mail o el número celular.',
                        'token' => '',
                        'user' => '',
                        'isNotConfirmed' => true,
                    ], 422);
                }

            } else { //finaliza validador
                $validator = Validator::make($request->all(), [
                    'email' => 'unique:users,email',
                    'phone' => 'unique:users',
                ]);

                if ($validator->fails()) {
                    if (!empty($validator->errors()->first('email'))) {
                        $user = $user->where('email', strtolower($request->email))->first();
                    } elseif (!empty($validator->errors()->first('phone'))) {
                        $user = $user->where('phone', $request->phone)->first();
                    }

                    $user->provider_id = $request->provider_id;
                    $user->provider = $request->provider;

                    $user->save();

                    $token = $user->createToken('ACCESS_PERSONAL_USER_TOKEN')->accessToken;

                    return response()->json([
                        'message' => __('app.users.register'),
                        'token' => $token,
                        'user' => new UserResouce($user),
                    ], 200);
                }

                $user->confirmed = true;
                $user->save();

                $fan = new ProfileUser([
                    'user_id' => $user->id,
                    'profile_id' => 1,
                    'status' => 1,
                ]);
                $fan->save();

                $token = $user->createToken('ACCESS_PERSONAL_USER_TOKEN')->accessToken;
                return response()->json([
                    'message' => __('app.users.register'),
                    'token' => $token,
                    'user' => new UserResouce($user),
                ], 200);
            }

        }

        $token = $user->createToken('ACCESS_PERSONAL_USER_TOKEN')->accessToken;
        return response()->json([
            'message' => __('app.users.login'),
            'token' => $token,
            'user' => new UserResouce($user),
        ], 200);
    }

    public function UserTeams(Request $request)
    {
        $user = $request->user();
        $team = Team::get();

        if (!$user) {
            return response()->json([
                'message' => __('app.users.invalid'),
                'user' => null,
            ], 422);
        }

        $teams = $user->teams;

        if ($request->has("teams_id")) {
            $user->teams()->detach();
            $teams_id = $request->teams_id;
        }
        $user->teams()->attach($teams_id);

        return response([
            'User' => new UserResouce($user),
        ]);

    }

    /**
     *
     * Método para validar los datos de acceso (email/phone y password) e iniciar la sesión del usuario.
     * El usuario debió hacer la validación del código enviado por email o teléfono al momento del registro para poder iniciar una sesión.
     *
     * @access public
     * @author Kevin Cifuentes
     *
     * @param AuthRequest $request email o teléfono y contraseña del usuario
     *
     * @return token de acceso e inicio de sesión
     */
    public function login(AuthRequest $request)
    {

        $user = null;
        if ($request->has('emailorphone')) {

            $emailOrPhone = strtolower($request->emailorphone);

            //$isValid = Helpers::isValidEmail($emailOrPhone);
            if ($user = User::where('email', $emailOrPhone)->first()) {

                if ($emailOrPhone != $user->email) {
                    return response()->json([
                        'message' => __('validation.auth_error'), 'user' => null,
                    ], 422);
                }

            } else if ($user = User::where('phone', $emailOrPhone)->first()) {
                if ($emailOrPhone != $user->phone) {
                    return response()->json([
                        'message' => __('validation.auth_error'), 'user' => null,
                    ], 422);
                }

            }

            if (!$user) {
                return response()->json([
                    'message' => __('validation.auth_error'),
                    'token' => null,
                    'user' => null,
                    'isNotConfirmed' => false,
                    'test' => "Entro en la primera opcion",
                ], 422);
            }

            if (!Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => __('validation.auth_error'),
                    'token' => null,
                    'user' => null,
                    'isNotConfirmed' => false,
                    'test' => "Entro en la segunda opcion",
                ], 422);
            }

            if (!$user->confirmed()) {
                $confirmationCode = Helpers::genCode(4);

                $user->confirmed_code = $confirmationCode;
                $user->confirmed = false;
                $user->save();

                Mail::to($user)->send(new UserValidationCode($user));
                $user->notify(new UserSendPhoneCode($user->confirmed_code));

                return response()->json([
                    'message' => __('validation.confirmed_error'),
                    'data' => [
                        'token' => '',
                        'user' => new UserResouce($user),
                        'isNotConfirmed' => true,
                    ],
                ], 401);
            }

        }

        $token = $user->createToken('ACCESS_PERSONAL_USER_TOKEN')->accessToken;

        return response()->json([
            'message' => __('app.users.login'),
            'token' => $token,
            'user' => new UserResouce($user),
            'isNotConfirmed' => false,
        ], 200);
    }

    /**
     * Destuye la sesión del usurio
     * @author Kevin Cifuentes
     * @access public
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => __('app.users.logout'),
        ], '200');
    }

    /**
     * @author Kevin Cifuentes
     * @access public
     */
    public function pusher()
    {
        $user = auth()->user();

        if ($user) {
            $pusher = new Pusher(config('broadcasting.connections.pusher.key'), config('broadcasting.connections.pusher.secret'), config('broadcasting.connections.pusher.app_id'));
            return $pusher->socket_auth(request()->input('channel_name'), request()->input('socket_id'));
        } else {
            header('', true, 403);
            echo "Forbidden";
            return;
        }
    }

    /**
     *
     * Método para recuperar contraseña.  Al solicitar recuperar la contraseña, se envía un código de validación por el medio que indique el usuario
     * Se debe solicitar como campos obligatorios el Email o el Teléfono, si uno esta presente el otro no es obligatorio.
     *
     * @access public
     * @param Request $request email o teléfono del usuario e ide del usuario
     *
     * @author Andy Padilla
     *
     * @return Código de verificación de 4 dígitos
     */
    public function recoveryPassword(Request $request)
    {
        $request->validate([
            'email' => 'required_without:phone',
            'phone' => 'required_without:email',
        ]);

        $user = null;

        if ($request->has('email')) {
            $user = User::where('email', strtolower($request->email))->first();
        } elseif ($request->has('phone')) {
            $user = User::where('phone', $request->phone)->first();
        } else {
            return response()->json([
                'message' => __('app.users.invalid_user'),
                'user' => null,
            ]);
        }

        if (!$user) {
            return response()->json([
                'message' => __('app.users.invalid_user'),
                'user' => null,
            ]);
        }

        $user->confirmed_code = Helpers::genCode(4);
        $user->confirmed = false;
        $user->save();

        if ($request->has('email')) {
            Mail::to($user)->send(new UserValidationCode($user, 'reset_psw'));
        }

        if ($request->has('phone')) {
            $user->notify(new UserSendPhoneCode($user->confirmed_code, 'reset_psw'));
        }

        return response()->json([
            'message' => __('app.users.send_code'),
            'user' => $user,
        ], 200);
    }

}
