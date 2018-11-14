<?php

namespace App\Http\Requests;

use App\Rules\IfExistsHashId;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string first_name
 * @property string last_name
 * @property string email
 * @property number phone
 * @property integer provider_id
 * @property string provider
 */
class StoreUserRequest extends FormRequest
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
        return [
            /*'provider'    => 'nullable',
            'provider_id' => 'required_with:provider|nullable',
            'password'    => 'required_without:provider|min:8',*/
        ];
    }
}

/*'city_id'    => [
                new IfExistsHashId('users')
            ],*/
