<?php

namespace App\Http\Api\V1\Controllers;

use App\Http\Api\ApiController;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Presenters\RolePresenter;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Prettus\Validator\Contracts\ValidatorInterface;

use App\Http\Api\V1\Requests\RoleCreateRequest;
use App\Http\Api\V1\Requests\RoleUpdateRequest;
use App\Interfaces\RoleRepository;
use App\Validators\RoleValidator;


/**
 * Class RolesController
 * @package App\Http\Api\V2\Controllers
 */
class RolesController extends ApiController
{

    /**
     * @var RoleRepository
     */
    protected $repository;

    /**
     * @var RoleValidator
     */
    protected $validator;

    /**
     * RolesController constructor.
     * @param RoleRepository $repository
     * @param RoleValidator $validator
     * @param RolePresenter $presenter
     */
    public function __construct(RoleRepository $repository, RoleValidator $validator, RolePresenter $presenter)
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
            $roles = $this->repository->all();
            return $this->respondWithSuccess($roles);

        } catch(\Exception $error) {
            return $this->respondInternalError($error->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $role = $this->repository->find($id);
            return $this->respondWithSuccess($role);

        } catch(ModelNotFoundException $error){
            return $this->respondNotFound($error->getMessage());
        } catch (\Exception $error){
            return $this->respondInternalError($error->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  RoleCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(RoleCreateRequest $request)
    {
        try {
            if(!$this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE)) {
                return $this->respondUnprocessableEntity($this->errorBag());
            }
            $role = $this->repository->create($request->all());
            return $this->respondCreated($role);

        } catch(\Exception $error) {
            return $this->respondInternalError($error->getMessage());
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  RoleUpdateRequest $request
     * @param  string            $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(RoleUpdateRequest $request, $id)
    {
        try {
            if(!$this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE)) {
                return $this->respondUnprocessableEntity($this->errorBag());
            }
            $role = $this->repository->update($request->all(), $id);
            return $this->respondCreated($role);

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
        $role = Role::withTrashed()->find($id);
        $role->restore();
        return $this->respondUpdated($role);
    }

    /**
     * @param $role_id
     * @param $permission_id
     * @return mixed
     */
    public function assignPermissionToRole($role_id, $permission_id)
    {
        try{
            $role = Role::find($role_id);
            $permission = Permission::find($permission_id);
            $role->givePermissionTo($permission);
            return $this->respondNoContent();

        } catch (\Exception $error){
            return $this->respondInternalError($error->getMessage());
        }
    }

    /**
     * @param $role_id
     * @param $user_id
     * @return mixed
     */
    public function assignRoleToUser($role_id, $user_id)
    {
        try{
            $role = Role::find($role_id);
            if(empty($role)) {
                return $this->respondNotFound('Role does not exist');
            }
            $user = User::find($user_id);
            if($user->hasRole($role)){
                return $this->respondUnprocessableEntity('User already assigned to this role');
            }
            $user->assignRole($role);
            return $this->respondNoContent();

        } catch (\Exception $error){
            return $this->respondInternalError($error->getMessage());
        }
    }

    /**
     * @param $role_id
     * @param $user_id
     * @return mixed
     */
    public function removeRoleFromUser($role_id, $user_id)
    {
        try{
            $role = Role::find($role_id);
            if(empty($role)) {
                return $this->respondNotFound('Role does not exist');
            }
            $user = User::find($user_id);
            $user->removeRole($role);
            return $this->respondWithSuccess($user);

        } catch (\Exception $error){
            return $this->respondInternalError($error->getMessage());
        }
    }

    // Documentation
    /**
     * @SWG\Get(
     *      path="/roles",
     *      operationId="get_all_roles",
     *      tags={"Roles & Permissions"},
     *      summary="Get list of all roles",
     *      description="Returns list of roles",
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *       ),
     *  )
     *
     */

    /**
     * @SWG\Get(
     *      path="/roles/1",
     *      operationId="get_single_role",
     *      tags={"Roles & Permissions"},
     *      summary="Get single role",
     *      description="Returns a single role",
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *       )
     * )
     *
     */
}
