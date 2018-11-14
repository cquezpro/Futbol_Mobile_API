<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostImage extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $isCover = false;
        $isAvatar = false;

        if($this->covers) {
            foreach ($this->covers as $cover) {
                if ($cover->active) {
                    $isCover = true;
                    break;
                }
            }
        }

        if($this->avatars) {
            foreach ($this->avatars as $avatar) {
                if ($avatar->active) {
                    $isAvatar = true;
                    break;
                }
            }
        }

        return [
            "path"     => $this->userImage->path,
            "hash_id"  => $this->hash_id,
            "isCover"  => $isCover,
            'isAvatar' => $isAvatar,
        ];
    }
}
