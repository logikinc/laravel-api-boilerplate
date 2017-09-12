<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\User;


/**
 * Class UserTransformer
 * @package namespace App\Transformers;
 */
class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['roles', 'permissions'];

    /**
     * Transform the \User entity
     * @param User $model
     *
     * @return array
     */
    public function transform(User $model)
    {
        $data = array_only($model->toArray(), $model->getFillable());
        return [
            'id'         => (int) $model->id,
            'type' => $model->getModelName(),
            'attributes' => $data
        ];
    }

    /**
     * @param User $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeRoles(User $model)
    {
        return $this->collection($model->roles, new RoleTransformer());
    }

    /**
     * @param User $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includePermissions(User $model)
    {
        return $this->collection($model->getAllPermissions(), new PermissionTransformer());
    }
}

