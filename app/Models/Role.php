<?php

namespace App\Models;

use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Spatie\Permission\Models\Role as RoleModel;

/**
 * Class Role
 * @package App\Models
 */
class Role extends RoleModel implements Transformable
{
    use TransformableTrait;

    /**
     * @var string
     */
    protected $modelName = 'Role';

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @return string
     */
    public function getModelName()
    {
        return $this->modelName;
    }

}
