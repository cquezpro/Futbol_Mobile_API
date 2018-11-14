<?php

namespace App\Http\Controllers;

use App\Helpers\UploadFiles;
use App\User;
use App\UserCover;
use App\UserImage;
use Illuminate\Http\Request;

class UserCoverController extends Controller
{
    public function index(User $user)
    {
        return response()->json([
            'message' => '',
            'data'    => $user->cover()->activeCover()->first(),
        ]);
    }

    /**
     * Se indica desde qué dispositvo ha sido cargado el archivo
     * 
     * @access public
     * @author Kevin Cifuentes
     * 
     * @param Request $request file image
     * @param User $user id del usuario
     */
    public function store(Request $request, User $user)
    {
        $file = null;

        if($request->has('type_sender')){
            $request->validate([
                'file_cover' => 'required' //|mimes:jpeg,png|dimensions:min_width=902,max_width=902'
            ]);

            $data = $request->file_cover;
            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);

            $file = base64_decode($data);
        }else{
            $request->validate([
                'file_cover' => 'required|file' //|mimes:jpeg,png|dimensions:min_width=902,max_width=902'
            ]);

            $file = $request->file_cover;
        }

        $fileName = UploadFiles::uploadFile($file, 'cover');

        $image = new UserImage([
            'path'        => $fileName,
            'form_device' => $request->form_device,
        ]);

        $user->covers()->update(['active' => false]);

        $image->user()->associate($user);
        $image->save();

        $cover = new UserCover([
            'active' => true,
        ]);

        $cover->userImage()->associate($image);
        $user->covers()->save($cover);

        return response()->json([
            'message' => "",
            "data"    => $user->cover()->activeCover()->get(),
        ]);
    }
}
