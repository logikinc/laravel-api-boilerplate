<?php

namespace App\Http\Api\Auth;

use App\Http\Api\ApiController;
use App\Http\Api\V1\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class LoginController
 * @package App\Http\Api\Auth
 */
class LoginController extends ApiController
{
    /**
     * @param LoginRequest $request
     * @param JWTAuth $JWTAuth
     * @return mixed
     */
    public function login(LoginRequest $request, JWTAuth $JWTAuth)
    {
        $credentials = $request->only(['email', 'password']);
        $device_id = $request->only('device_id');

        try {
            $token = $JWTAuth->attempt($credentials, $device_id);

            if(!$token) {
                throw new AccessDeniedHttpException();
            }

        } catch (JWTException $e) {
            throw new HttpException(500);
        }
        return $this->respondWithSuccess(['status' => 'ok', 'token' => $token]);
    }

    //Documentation

    /**
     * @SWG\Post(
     *     path="/auth/login",
     *     tags={"Authentication"},
     *     operationId="loginInUser",
     *     summary="User Login",
     *     description="Provides an email and a password and expects a JWT token in return",
     *     @SWG\Parameter(
     *         name="email",
     *         in="formData",
     *         type="string",
     *         description="Email address associated with the ondash user",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         type="string",
     *         description="Password associated with the ondash user",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         name="device_id",
     *         in="formData",
     *         type="string",
     *         description="Device that is attempting to connect to the API",
     *         required=false
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful Request"
     *     ),
     *     @SWG\Response(
     *         response=403,
     *         description="Invalid Credentials"
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Invalid Request"
     *     )
     * )
     */

    /**
     * @SWG\Post(
     *     path="/auth/recovery",
     *     tags={"Authentication"},
     *     operationId="forgotPassword",
     *     summary="Forgot Password",
     *     description="Sends an email to the user with a forgot password token in order for the user to reset their password",
     *     @SWG\Parameter(
     *         name="email",
     *         in="formData",
     *         type="string",
     *         description="Email address associated with the ondash user",
     *         required=true
     *     ),
     *     @SWG\Response(
     *         response=204,
     *         description="Successful Request"
     *     ),
     *     @SWG\Response(
     *         response=404,
     *         description="No user with given email"
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Invalid Request"
     *     )
     * )
     */

    /**
     * @SWG\Post(
     *     path="/auth/reset",
     *     tags={"Authentication"},
     *     operationId="resetPassword",
     *     summary="User Password Reset",
     *     description="Resets a user password given a password reset token, an email and a confirmed password",
     *     @SWG\Parameter(
     *         name="token",
     *         in="formData",
     *         type="string",
     *         description="Token that comes from the forgot password email",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         name="email",
     *         in="formData",
     *         type="string",
     *         description="Email address associated with the ondash user",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         type="string",
     *         description="The new password",
     *         required=true
     *     ),
     *     @SWG\Parameter(
     *         name="password_confirmation",
     *         in="formData",
     *         type="string",
     *         description="Retype the password",
     *         required=true
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Successful Request"
     *     ),
     *     @SWG\Response(
     *         response=422,
     *         description="Invalid Request"
     *     )
     * )
     */
}
