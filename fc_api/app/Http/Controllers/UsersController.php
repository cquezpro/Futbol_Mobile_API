<?php

namespace App\Http\Controllers;

use App\GeneralUserProfile;
use App\Helpers\Helpers;
use App\Http\Requests\CodeResetPasswordRequest;
use App\Http\Requests\UserValidationCodeRequest;
use App\Http\Resources\User as UserResources;
use App\Http\Resources\UserCollection;
use App\Mail\UserValidationCode;
use App\Notifications\UserSendPhoneCode;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

/**
 * <b>Name=</b>  Users<br>
 * <b>Description=</b>  Metodos para actualizar datos del usurio y validar códigos de verificación.
 *
 */
class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except([
            'validateCode',
            'getCodeResetPassword',
        ]);
    }

    /**
     *
     * @author Kevin Cifuentes
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = null;

        if (!$request->has('q')) {
            $users = User::where('id', '<>', $request->user()->id)->get();
        } elseif ($request->has('q')) {
            $users = User::where('id', '<>', $request->user()->id)->where(DB::raw("concat(first_name, ' ' ,last_name)"), 'like', '%' . $request->q . '%')->get();
        }
        //TODO Agregar filtros por ubicación.

        return response()->json([
            'count' => $users->count(),
            'users' => new UserCollection($users),
        ], 200);
    }

    public function store()
    {

    }

    /**
     * Retorna la información de un usuario (datos personales, seguidores e información técnica), se requiere que el usuario tenga una sesión activa.
     *
     * @access public
     * @author Kevin Cifuentes
     *
     * @param Request $request
     * @param Request $user id del usuario
     *
     * @return $user datos del usuario
     */
    public function show(Request $request, $user)
    {
        $userInfo = User::find($user);

        return response()->json([
            'user' => new UserResources($userInfo),
        ]);
    }

    public function update($user)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @access public
     * @author Kevin Cifuentes
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Método para actualizar datos del usuario: email y/o teléfono, se deben confirmar los cambios con la validación
     * de un código de verificación enviado por el medio indicado (email o teléfono).
     *
     * @author Andy Padilla
     * @param Request $request email y/o phone
     * @param  Request $user
     * @return $user datos actualizados: email y/o phone del usuario.
     */
    public function updateUserData(Request $request, $user)
    {
        $request->validate([
            'email' => 'unique:users,email|required_without:phone',
            'phone' => 'unique:users,phone|required_without:email',
        ]);

        $user = User::find($user); //Find user

        if (!$user) {
            return response()->json([
                'message' => __('app.users.invalid'),
                'user' => null,
            ], 422);
        }

        $user->confirmed_code = Helpers::genCode(4);
        $user->confirmed = false;
        $user->save();

        if ($request->has('email')) {
            $user->email = $request->email;
        }

        Mail::to($user)->send(new UserValidationCode($user, 'update_mail_phone'));

        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }

        $user->notify(new UserSendPhoneCode($user->confirmed_code, 'update_mail_phone'));

        $user->save();

        return response()->json([
            'message' => __('app.users.success_update'),
            'user' => $user,
        ], 200);
    }

    /**
     * Método para verificar y/o validar el código de verificación enviado al usuario por el medio indicado (email o teléfono)
     * luego de un registro, actualización de datos y recuperación de contraseña.
     * El código de verificación contiene 4 dígitos generados aleatoriamente.
     * En caso que el usuario haya solicitado recuperación de contraseña, se genera una contraseña aleatoria de 8 caracteres por medio del email o teléfono.
     *
     * @param UserValidationCodeRequest $request
     * @param $user
     * @param $is_reset_psw boolean campo no obligatorio. su valor debe ser true cuando se realice proceso de recuperación de contraseña.
     * @param null $is_reset_psw
     *
     * @author Andy Padilla
     *  [10/07/2018] By Andy
     *      Se agrega parámetro opcional para determinar si es un reset password o un registro.
     *      Se encrypt la nueva contraseña del usuario y se asigna al objeto User.
     *
     * @author Kevin Cifuentes
     *  [10/07/2018] By Kevin Cifuentes
     *      Se corrige la funcionlidad
     *
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function validateCode(UserValidationCodeRequest $request, $user)
    {

        $user = User::find($user);

        if (!$user) {
            return abort('422', __('app.users.invalid'));
        }

        if ($user->confirmed) {
            return abort('422', __('app.users.confirmed'));
        }

        if ($user->confirmed_code != $request->confirmed_code) {
            return abort('422', __('app.users.invalid_code'));
        }

        $user->confirmed_code = null;
        $user->confirmed = true;

        if ($request->has('is_reset_psw')) {
            $user->is_new_password = true;

            $newpass = str_random(8);
            $user->password = Hash::make($newpass);

            $user->save();

            Mail::to($user)->send(new UserValidationCode($user, 'send_new_psw', $newpass));
            $user->notify(new UserSendPhoneCode($newpass, 'send_new_psw'));

        } else {
            $user->save();
        }

        $token = $user->createToken('ACCESS_PERSONAL_USER_TOKEN')->accessToken;

        return response()->json([
            'message' => trans('app.users.user_code_validation_success'),
            'token' => $token,
            'user' => $user,
        ], 200);
    }

    /**
     * Validate reset password code
     * Returns the validation code for resetting the password
     *
     * @author Kevin Cifuentes
     * @param CodeResetPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCodeResetPassword(CodeResetPasswordRequest $request)
    {
        $user = User::where('email', $request->emailorphone)
            ->orWhere('phone', $request->emailorphone)
            ->first();

        if (!$user) {
            abort(404, trans('app.users.email_phone_not_exits'));
        }

        $confirmationCode = Helpers::genCode(4);

        $user->confirmed_code = $confirmationCode;
        $user->confirmed = false;
        $user->is_new_password = true;

        $user->save();

        Mail::to($user)->send(new UserValidationCode($user, 'reset_psw'));
        $user->notify(new UserSendPhoneCode($confirmationCode, 'reset_psw'));

        return response()->json([
            'validate_code' => $confirmationCode,
            'user' => $user->id,
        ]);

    }

    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'new_psw' => 'required',
        ]);

        $user->password = Hash::make($request->new_psw);

        $user->save();

        $token = $user->createToken('ACCESS_PERSONAL_USER_TOKEN')->accessToken;

        return response()->json([
            'message' => __('app.users.login'),
            'token' => $token,
            'user' => new UserResources($user),
            'isNotConfirmed' => false,
        ], 200);

    }

    /**
     * Método para agregar una foto de perfil del usuario (avatar).
     * Corregir esta ruta con el fin de agregar la imagen del avatar en la tabla de images y asociarla al usuario, tener en cuenta el hecho de que si seselecciona una imagen previamente guardada como avatar se espera el image_id
     * @param Request $request
     * @param $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function saveAvatar(Request $request, $user)
    {

        $request->validate([
            'avatar_file' => 'required|file|image',
        ]);

        $fileName = Storage::disk('spaces')->putFile('users_media_images', request()->avatar_file, 'public');

        $user = User::find($user);
        $generalInfo = null;

        $generalInfo = !$user->generalInformation()->exists()
        ? new GeneralUserProfile
        : $user->generalInformation()->first();

        $generalInfo->avatar = $fileName;

        $user->generalInformation()->save($generalInfo);

        return response($generalInfo);
    }

    /**
     * Método para editar y/o actualizar los nombres y biografia del usuario.
     * @param string first_name Primer nombre del usuario, es obligatorio
     * @param string last_name Segundo nombre del usuario, es obligatorio
     * @param string biography Biografía del usuario, no es obligatorio, este campo puede almacenar etiquetas html.
     * @param $user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateBiographyAndNames(Request $request, $user)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
        ]);

        $user = User::find($user);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->nick_name = $request->nick_name;
        $user->esport_futbol = $request->esport_futbol;
        $user->save();
        $gameusers = $request->gameusers;
        $user->games()->attach($gameusers);

        $generalInfo = null;
        $generalInfo = !$user->generalInformation()->exists()
        ? new GeneralUserProfile()
        : $user->generalInformation()->first();

        $generalInfo->biography = $request->biography;

        $user->generalInformation()->save($generalInfo);

        $user->load('generalInformation');
        return response()->json([
            "user" => new UserResources($user),
        ]);
    }
}
