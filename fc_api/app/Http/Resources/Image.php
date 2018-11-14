<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Image extends JsonResource
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

        foreach ($this->covers as $cover) {
            if ($cover->active) {
                $isCover = true;
                break;
            }
        }

        foreach ($this->avatars as $avatar) {
            if ($avatar->active) {
                $isAvatar = true;
                break;
            }
        }

        return [
            "path"        => $this->path,
            "form_device" => $this->form_device,
            "hash_id"     => $this->hash_id,
            "id"          => $this->id,
            'isAvatar'    => $isAvatar,
            'isCover'     => $isCover,
        ];
    }
}
