<?php

namespace App\Http\Api\V1\Controllers;

use App\Http\Api\ApiController;

use App\Models\Permission;
use App\Presenters\PermissionPresenter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Prettus\Validator\Contracts\ValidatorInterface;

use App\Http\Api\V1\Requests\PermissionCreateRequest;
use App\Http\Api\V1\Requests\PermissionUpdateRequest;
use App\Interfaces\PermissionRepository;
use App\Validators\PermissionValidator;


/**
 * Class PermissionsController
 * @package App\Http\Api\V2\Controllers
 */
class PermissionsController extends ApiController
{

    /**
     * @var PermissionRepository
     */
    protected $repository;

    /**
     * @var PermissionValidator
     */
    protected $validator;

    /**
     * PermissionsController constructor.
     * @param PermissionRepository $repository
     * @param PermissionValidator $validator
     * @param PermissionPresenter $presenter
     */
    public function __construct(PermissionRepository $repository, PermissionValidator $validator, PermissionPresenter $presenter)
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
            $permissions = $this->repository->all();
            return $this->respondWithSuccess($permissions);

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
            $permission = $this->repository->find($id);
            return $this->respondWithSuccess($permission);

        } catch(ModelNotFoundException $error){
            return $this->respondNotFound($error->getMessage());
        } catch (\Exception $error){
            return $this->respondInternalError($error->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PermissionCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionCreateRequest $request)
    {
        try {
            if(!$this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE)) {
                return $this->respondUnprocessableEntity($this->errorBag());
            }
            $permission = $this->repository->create($request->all());
            return $this->respondCreated($permission);

        } catch(\Exception $error) {
            return $this->respondInternalError($error->getMessage());
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PermissionUpdateRequest $request
     * @param  string            $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(PermissionUpdateRequest $request, $id)
    {
        try {
            if(!$this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE)) {
                return $this->respondUnprocessableEntity($this->errorBag());
            }
            $permission = $this->repository->update($request->all(), $id);
            return $this->respondCreated($permission);

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
        $permission = Permission::withTrashed()->find($id);
        $permission->restore();
        return $this->respondWithSuccess($permission);
    }
}
