<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Spatie\Permission\Models\Permission;

/**
 * Class PermissionTransformer
 * @package namespace App\Transformers;
 */
class PermissionTransformer extends TransformerAbstract
{
    /**
     * Transform the \Permission entity
     * @param Permission $model
     *
     * @return array
     */
    public function transform(Permission $model)
    {
        $data = array_only($model->toArray(), ['name']);
        return [
            'id'         => (int) $model->id,
            'type' => 'Permission',
            'attributes' => $data
        ];
    }
}
