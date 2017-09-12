<?php

namespace App\Http\Api\Auth;

use App\Http\Api\ApiController;
use App\Http\Api\V1\Requests\Auth\ForgotPasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Password;

/**
 * Class ForgotPasswordController
 * @package App\Http\Api\Auth
 */
class ForgotPasswordController extends ApiController
{
    /**
     * @param ForgotPasswordRequest $request
     * @return mixed
     */
    public function sendResetEmail(ForgotPasswordRequest $request)
    {
        $user = User::where('email', '=', $request->get('email'))->first();

        if(!$user) {
            return $this->respondNotFound('Email has not been sent');
        }

        $broker = $this->getPasswordBroker();
        $sendingResponse = $broker->sendResetLink($request->only('email'));

        if($sendingResponse !== Password::RESET_LINK_SENT) {
            return $this->respondInternalError('An error has occurred');
        }

        return $this->respondNoContent();
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    private function getPasswordBroker()
    {
        return Password::broker();
    }
}
