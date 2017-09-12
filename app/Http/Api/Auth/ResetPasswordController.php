<?php

namespace App\Http\Api\Auth;

use App\Http\Api\ApiController;
use App\Http\Api\V1\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ResetPasswordController
 * @package App\Http\Api\Auth
 */
class ResetPasswordController extends ApiController
{
    /**
     * @param ResetPasswordRequest $request
     * @param JWTAuth $JWTAuth
     * @return mixed
     */
    public function resetPassword(ResetPasswordRequest $request, JWTAuth $JWTAuth)
    {
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->reset($user, $password);
            }
        );

        if($response !== Password::PASSWORD_RESET) {
            throw new HttpException(500);
        }

        $user = User::where('email', '=', $request->get('email'))->first();

        return $this->respondWithSuccess(['status' => 'ok', 'token' => $JWTAuth->fromUser($user)]);
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }

    /**
     * Get the password reset credentials from the request.
     *
     * @param  ResetPasswordRequest  $request
     * @return array
     */
    protected function credentials(ResetPasswordRequest $request)
    {
        return $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function reset($user, $password)
    {
        $user->password = $password;
        $user->save();
    }
}
