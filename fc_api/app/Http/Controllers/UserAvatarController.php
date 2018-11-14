<?php

namespace App\Http\Controllers;

use App\Helpers\UploadFiles;
use App\User;
use App\UserAvatar;
use App\UserImage;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

/**
 * @author Kevin cifuentes
 */
class UserAvatarController extends Controller
{
    public function show(User $user)
    {
        return response()->json([
            'message' => '',
            "data"    => $user->avatar()->activeAvatar()->first(),
        ]);
    }

    /**
     * Ruta par cambiar el avatar del usuario, esta ruta acepta como parámetro en la URL el hash_id del usuario.
     * param: file_avatar | file | debe ser JPEG, PHP, dimensiones mínimas width_max 150px, height_max 150px.
     * param: form_device | string | sus valores son "web" o  "mobile" y se usa para especificar desde que dispositivo se esta subiendo el archivo.|
     *
     * @access public
     * @author Kevin Cifuentes
     * 
     * @param Request $request
     * @param User $user
     *
     * @return $user avatar 
     */
    public function store(Request $request, User $user)
    {
        $file = null;

        if($request->has('type_sender')){
            $request->validate([
                'file_avatar' => 'required' //|mimes:jpeg,png|dimensions:min_width=902,max_width=902'
            ]);

            $data = $request->file_avatar;
            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);

            $file = base64_decode($data);
        }else{
            $request->validate([
                'file_avatar' => 'required|file' //|mimes:jpeg,png|dimensions:min_width=902,max_width=902'
            ]);
            $file = $request->file_avatar;
        }

        $fileName = UploadFiles::uploadFile($file, 'avatar');

        $image = new UserImage([
            'path'        => $fileName,
            'form_device' => $request->form_device,
        ]);

        $user->avatars()->update(['active' => false]);

        $image->user()->associate($user);
        $image->save();

        $avatar = new UserAvatar([
            'active' => true,
        ]);

        $avatar->userImage()->associate($image);
        $user->avatars()->save($avatar);

        //TODO Agregar Notificación Push para notificar a los usuarios

        return response()->json([
            'message' => "",
            "data"    => $user->avatar()->activeAvatar()->get(),
        ]);
    }
}
