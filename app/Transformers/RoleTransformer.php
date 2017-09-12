<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Spatie\Permission\Models\Role;


/**
 * Class RoleTransformer
 * @package namespace App\Transformers;
 */
class RoleTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['permissions'];
    /**
     * Transform the \Role entity
     * @param Role $model
     *
     * @return array
     */
    public function transform(Role $model)
    {
        $data = array_only($model->toArray(), ['name']);
        return [
            'id'         => (int) $model->id,
            'type' => 'Role',
            'attributes' => $data
        ];
    }

    /**
     * @param Role $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includePermissions(Role $model)
    {
        return $this->collection($model->permissions, new PermissionTransformer());
    }
}
