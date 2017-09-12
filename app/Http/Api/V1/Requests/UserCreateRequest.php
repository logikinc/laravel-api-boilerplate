<?php

namespace App\Http\Api\V1\Requests;

use Dingo\Api\Http\FormRequest;

/**
 * Class UserCreateRequest
 * @package App\Http\Requests
 */
class UserCreateRequest extends FormRequest
{
    /**
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
            'first_name' => 'required',
            'last_name' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
            'email' => 'required|unique:users'
        ];
    }
}
