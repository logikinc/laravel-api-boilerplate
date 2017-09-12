<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class Release
 * @package App\Models
 */
class Release extends Model implements Transformable
{
    use TransformableTrait, SoftDeletes;

    protected $modelName = 'Release';

    protected $fillable = [
        'platform',
        'application_id',
        'version',
        'increment',
        'branch',
        'commit_id',
        'tag',
        'slug',
        'repo_uri',
        'app_name',
        'icon_uri',
        'bin_uri',
        'md5_checksum',
        'is_enabled',
        'is_visible',
        'min_api_level',
        'max_api_level',
        'app_description',
        'app_download_size',
        'delivery_channel',
    ];

    /**
     * @return string
     */
    public function getModelName()
    {
        return $this->modelName;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deviceProfileReleases()
    {
        return $this->hasMany('App\Models\DeviceProfileReleases');
    }
}
