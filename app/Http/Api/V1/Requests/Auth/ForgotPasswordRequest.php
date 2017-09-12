<?php

namespace App\Http\Api\V1\Requests\Auth;

use Dingo\Api\Http\FormRequest;

/**
 * Class ForgotPasswordRequest
 * @package App\Http\Requests\Auth
 */
class ForgotPasswordRequest extends FormRequest
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
            'email' => 'required|email'
        ];
    }
}
