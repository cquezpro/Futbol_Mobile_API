<?php

namespace App\Http\Controllers;

use App\Helpers\UploadFiles;
use App\Http\Resources\ImageCollection;
use App\Http\Resources\UserImage as UserImageResource;
use App\User;
use App\UserImage;
use Illuminate\Http\Request;

/**
 * @author Kevin Cifuentes
 */
class UserImageController extends Controller
{
    /**
     * @param User $user
     */
    public function index(User $user)
    {
        $images = $user->images;
        return new ImageCollection($images);
    }

    /**
     * @param User $user
     * @param Request $request
     */
    public function create(Request $request)
    {
        /*$request->validate([
        'file_image' => 'required|file' //|mimes:jpeg,png|dimensions:min_width=902,max_width=902'
        ]);*/

        $user = $request->user();

        if ($request->has('type_sender')) {
            $data = $request->file_image;
            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);

            $file = base64_decode($data);

            $fileName = UploadFiles::uploadFile($file, 'feed');
            $image = new UserImage([
                'path' => $fileName,
                'form_device' => $request->form_device,
            ]);

            $image->user()->associate($user);
            $image->save();

            return new UserImageResource($image);

        } else {
            $request->validate([
                'file_image' => 'mimes:mpeg,ogg,mp4,webm,3gp,mov,flv,avi,wmv,ts,png,jpg,jpeg|max:100040|required|file',
            ]);

            $mimeType = $request->file('file_image')->getClientMimeType();
            $post = strpos($mimeType, 'video');

            $file = $request->file_image;

            if ($post === false) {
                $fileName = UploadFiles::uploadFile($file, 'feed');
                $image = new UserImage([
                    'path' => $fileName,
                    'form_device' => $request->form_device,
                ]);

                $image->user()->associate($user);
                $image->save();

                return new UserImageResource($image);

            } else {
                $fileName = UploadFiles::uploadFile($request->file_image, 'feed', 'video');

                $video = new UserVideo([
                    'path' => $fileName,
                    'form_device' => $request->form_device,
                ]);

                $video->user()->associate($user);
                $video->save();

                return new UserVideoResource($video);

            }
        }
    }

    /**
     * @param Request $request
     * @param UserImage $image
     */
    public function delete(Request $request, UserImage $image)
    {
        if ($request->user()->id != $image->user->id) {
            return response(['message' => __('validation.error_users_id')], 409);
        }

        $image->delete();

        return response()->json([
            'message' => __('app.users.del_user_img'),
            "data" => [],
        ]);
    }
}
