<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Vinkla\Hashids\Facades\Hashids;

class CreatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        if ($this->has('images')) {
            foreach ($this->images as $image) {
                Validator::make(['id' => $image], [
                    'id' => 'exists:user_images',
                ])->validate();
            }
        }

        if ($this->has('videos')) {
            foreach ($this->videos as $video) {
                Validator::make(['id' => $video], [
                    'id' => 'exists:user_videos',
                ])->validate();
            }
        }
        return $rules;
    }
}
