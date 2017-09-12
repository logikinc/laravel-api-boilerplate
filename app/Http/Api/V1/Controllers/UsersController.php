<?php

namespace App\Http\Api\V1\Controllers;

use App\Http\Api\ApiController;

use App\Models\Role;
use App\Models\User;
use App\Presenters\UserPresenter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Hash;
use Prettus\Validator\Contracts\ValidatorInterface;
use App\Http\Api\V1\Requests\UserCreateRequest;
use App\Http\Api\V1\Requests\UserUpdateRequest;
use App\Interfaces\UserRepository;
use App\Validators\UserValidator;
use DB;
use Tymon\JWTAuth\Facades\JWTAuth;


/**
 * Class UsersController
 * @package App\Http\Api\V2\Controllers
 */
class UsersController extends ApiController
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
     * UsersController constructor.
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $users = $this->repository->all();
            return $this->respondWithSuccess($users);
        } catch(\Exception $error) {
            return $this->respondInternalError($error->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = $this->repository->find($id);
            return $this->respondWithSuccess($user);
        } catch(ModelNotFoundException $error){
            return $this->respondNotFound($error->getMessage());
        } catch (\Exception $error){
            return $this->respondInternalError($error->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UserCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(UserCreateRequest $request)
    {
        try {
            if(!$this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE)) {
                return $this->respondUnprocessableEntity($this->errorBag());
            }
            DB::beginTransaction();
            $user = $this->repository->create([
                'email' => $request->email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'password' => Hash::make($request->password)
            ]);
            $this->assignRoleToUser($user['data']['id'], $request->role_id);
            DB::commit();
            return $this->respondCreated($user);

        } catch(\Exception $error) {
            DB::rollback();
            return $this->respondInternalError($error->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserUpdateRequest $request
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, $id)
    {
        try {
            if(!$this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE)) {
                return $this->respondUnprocessableEntity($this->errorBag());
            }
            if($this->user->hasRole('Admin') || $this->user->id == $id){
                $user = $this->repository->update($request->all(), $id);
                return $this->respondUpdated($user);
            }
            return $this->respondUnauthorized('You do not have permission to update this user');
        } catch(\Exception $error) {
            return $this->respondInternalError($error->getMessage());
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $this->repository->delete($id);
        return $this->respondNoContent();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function restore($id)
    {
        $user = User::withTrashed()->find($id);
        $user->restore();
        return $this->respondWithSuccess($user);
    }

    /**
     * @param $user_id
     * @param $role_id
     * @return bool
     */
    public function assignRoleToUser($user_id, $role_id)
    {
        $user = User::find($user_id);
        $role = Role::find($role_id);
        $user->assignRole(Role::find($role));
        return true;
    }

    /**
     * @SWG\Get(
     *      path="/users",
     *      operationId="get_all_users",
     *      tags={"Users"},
     *      summary="Get list of all users",
     *      description="Returns list of users",
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *       ),
     *  )
     *
     */

    /**
     * @SWG\Get(
     *      path="/users/1",
     *      operationId="get_single_user",
     *      tags={"Users"},
     *      summary="Get single city",
     *      description="Returns a single user",
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *       )
     * )
     *
     */

    /**
     * @SWG\Post(
     *     path="/users",
     *     tags={"Users"},
     *     operationId="create_user",
     *     summary="Create a user",
     *     description="Creates a user given the form request",
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
     *     path="/users/1",
     *     tags={"Users"},
     *     operationId="update_user",
     *     summary="Update a user",
     *     description="Update a user given the form request",
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
     *     path="/users/1/delete",
     *     tags={"Users"},
     *     operationId="delete_user",
     *     summary="Delete a user",
     *     description="Delete a user",
     *     @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *       )
     * )
     */

    /**
     * @SWG\Post(
     *     path="/users/1/recover",
     *     tags={"Users"},
     *     operationId="recover_user",
     *     summary="Recover a user",
     *     description="Recover a user",
     *     @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *       )
     * )
     */
}
