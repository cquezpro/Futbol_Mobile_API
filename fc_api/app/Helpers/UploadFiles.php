<?php
/**
 * Created by PhpStorm.
 * User: Ing Kevin Cifuentes
 * Date: 21/05/2018
 * Time: 11:08 AM
 */

namespace App\Helpers;


use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UploadFiles
{
    /**
     * Metodo para subir las imagenes al servidor, tener en cuenta que este metodo sube solo imagenes para el
     * avatar, cover y feed.
     *
     * @author @Kevin Cifuentes 
     * @param file $file Archivo de imagen a subir al servidor.
     * @param string $action cadena que especifica la accion o modelo a usar, posibles valores "avatar", "cover" ó "feed"
     * @param string $type
     * @return mixed
     */
    public static function uploadFile($file, $action = 'avatar', $type = "images")
    {

        $folder = $type == "images" ? env('DO_SPACES_FOLDER') : env('DO_SPACES_VIDEOS_FOLDER');
        $fileName = null;
        $fileTemp = null;
        if ($type == "images") {
            $img = Image::make($file);
            switch ($action) {
                case 'avatar':
                    $folder .= '/user_avatares';
                    $img->resize(480, 480, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    break;
                case 'cover':
                    $folder .= '/user_covers';
                    $img->resize($img->width(), $img->height(), function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    break;
                case 'feed':
                    $folder .= '/iamge_feeds';
                    if ($img->width() > 1080) {
                        $img->resize(1080, null, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });
                    } else {
                        $img->resize($img->width(), $img->height(), function ($constraint) {
                            $constraint->upsize();
                        });
                    }
                    break;
            }

            $name = 'temp' . time() . '.jpg';
            $path_temp = public_path() . '/images/' . $name;

            $img->save($path_temp, 95);
            $fileTemp = new File($path_temp);

            $fileName = Storage::disk('spaces')
                ->putFile($folder, $fileTemp, 'public');

            Storage::disk('local')->delete('/images/' . $name);

        } elseif ($type == 'video') {
            $duration = Carbon::createFromTimeString(self::getDuration($file));
            $durationLimit = Carbon::createFromTimeString("00:20:00");

            if ($duration->gte($durationLimit))
                abort(409, __('app.users.video_duration_error'));

            $fileName = Storage::disk('spaces')
                ->putFile($folder, $file, 'public');
        }

        return $fileName;
    }

    private static function getDuration($full_video_path)
    {
        $getID3 = new \getID3();
        $file = $getID3->analyze($full_video_path);
        $playtime_seconds = $file['playtime_seconds'];
        $duration = date('H:i:s.v', $playtime_seconds);
        return $duration;
    }
}