<?php

namespace App\Models;

use Spatie\Permission\Models\Permission as PermissionModel;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Permission
 * @package App\Models
 */
class Permission extends PermissionModel implements Transformable
{
    use TransformableTrait;

    protected $modelName = 'Permission';

    protected $fillable = ['name', 'guard_name'];

    /**
     * @return string
     */
    public function getModelName()
    {
        return $this->modelName;
    }

}
