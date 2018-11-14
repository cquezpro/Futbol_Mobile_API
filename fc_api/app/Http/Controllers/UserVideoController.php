<?php

namespace App\Http\Controllers;

use App\Helpers\UploadFiles;
use App\Http\Resources\UserVideoResource;
use App\User;
use App\UserVideo;
use Illuminate\Http\Request;

class UserVideoController extends Controller
{
    public function create(Request $request, User $user)
    {
        $request->validate([
            'file_video' => 'mimes:mpeg,ogg,mp4,webm,3gp,mov,flv,avi,wmv,ts|max:100040|required',
        ]);

        $fileName = UploadFiles::uploadFile($request->file_video, 'feed', 'video');

        $video = new UserVideo([
            'path'        => $fileName,
            'form_device' => $request->form_device,
        ]);

        $video->user()->associate($user);
        $video->save();

        return new UserVideoResource($video);
    }

    public function delete(Request $request, UserVideo $video)
    {

        try {
            if ($request->user()->id != $video->user->id)
                return response(['message' => __('validation.error_users_id')], 409);

            $video->delete();
        } catch (\Exception $e) {
            return response($e);
        }

        return response()->json([
            'message' => __('app.users.del_video')
        ]);
    }
}
