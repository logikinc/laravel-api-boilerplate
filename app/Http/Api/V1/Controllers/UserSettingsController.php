<?php

namespace App\Http\Api\V1\Controllers;

use App\Http\Api\ApiController;

use App\Http\Api\V1\Requests\ChangeUserPasswordRequest;
use App\Http\Api\V1\Requests\UserUpdateRequest;
use App\Mail\PasswordChanged;
use App\Presenters\UserPresenter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Prettus\Validator\Contracts\ValidatorInterface;
use App\Interfaces\UserRepository;
use App\Validators\UserValidator;
use Tymon\JWTAuth\Facades\JWTAuth;
use DB;


/**
 * Class UserSettingsController
 * @package App\Http\Api\V2\Controllers
 */
class UserSettingsController extends ApiController
{

    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @var UserValidator
     */
    protected $validator;

    protected $user;

    /**
     * UserSettingsController constructor.
     * @param UserRepository $repository
     * @param UserValidator $validator
     * @param UserPresenter $presenter
     */
    public function __construct(UserRepository $repository, UserValidator $validator, UserPresenter $presenter)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->repository->setPresenter($presenter);
    }

    private function getUser()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showCurrentUser()
    {
        try {
            $this->getUser();
            $user = $this->repository->find($this->user->id);
            return $this->respondWithSuccess($user);

        } catch(\Exception $error) {
            return $this->respondWithError($error->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserUpdateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function updateCurrentUser(UserUpdateRequest $request)
    {
        try {
            $this->getUser();
            if(!$this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE)) {
                return $this->respondUnprocessableEntity($this->errorBag());
            }
            $user = $this->repository->update($request->all(), $this->user->id);
            return $this->respondUpdated($user);

        } catch(\Exception $error) {
            return $this->respondWithError($error->getMessage());
        }
    }


    /**
     * @param ChangeUserPasswordRequest $request
     * @return mixed
     */
    public function changeCurrentUserPassword(ChangeUserPasswordRequest $request)
    {
        $this->getUser();
        DB::beginTransaction();
        try {
            $current_password = $this->user->password;

            if (!Hash::check($request->old_password, $current_password)) {

                return $this->respondUnprocessableEntity(['old_password' => 'Old password is incorrect']);
            }

            $this->user->password = $request->password;
            $updated = $this->user->save();

            if (!$updated) {
                DB::rollBack();
                return $this->respondWithError('Something went wrong, the password was not saved');
            }

            Mail::to($request->user())->send(new PasswordChanged($this->user, $request));

            if (count(Mail::failures()) > 0) {

                DB::rollback();
                return $this->respondWithError('Error in Mail');
            }

            DB::commit();
            return $this->respondUpdated($this->repository->find($this->user->id));
        }catch (\Exception $error) {
            return $this->respondInternalError($error->getMessage());
        }
    }

    /**
     * @SWG\Get(
     *      path="/user",
     *      operationId="get_current_user",
     *      tags={"User Settings"},
     *      summary="Get current users profile",
     *      description="Returns current users profile",
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *       ),
     *  )
     *
     */

    /**
     * @SWG\Post(
     *     path="/user/update",
     *     tags={"User Settings"},
     *     operationId="update_current_user",
     *     summary="Updates the current user",
     *     description="Updates the current user",
     *     @SWG\Parameter(
     *         name="name",
     *         in="formData",
     *         type="string",
     *         description="",
     *         required=true,
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *       )
     * )
     */

    /**
     * @SWG\Post(
     *     path="/user/change_password",
     *     tags={"User Settings"},
     *     operationId="change_current_user_password",
     *     summary="Updates the current users password",
     *     description="Updates the current users password",
     *     @SWG\Parameter(
     *         name="old_password",
     *         in="formData",
     *         type="string",
     *         description="",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="password",
     *         in="formData",
     *         type="string",
     *         description="",
     *         required=true,
     *     ),
     *     @SWG\Parameter(
     *         name="password_confirmation",
     *         in="formData",
     *         type="string",
     *         description="",
     *         required=true,
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *       )
     * )
     */

}
