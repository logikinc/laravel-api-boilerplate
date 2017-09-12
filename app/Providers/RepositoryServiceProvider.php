<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * @package App\Providers
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Interfaces\ProfileRepository::class, \App\Repositories\ProfileRepositoryEloquent::class);
        $this->app->bind(\App\Interfaces\OperatorRepository::class, \App\Repositories\OperatorRepositoryEloquent::class);
        $this->app->bind(\App\Interfaces\CountryRepository::class, \App\Repositories\CountryRepositoryEloquent::class);
        $this->app->bind(\App\Interfaces\CityRepository::class, \App\Repositories\CityRepositoryEloquent::class);
        $this->app->bind(\App\Interfaces\DeviceModelRepository::class, \App\Repositories\DeviceModelRepositoryEloquent::class);
        $this->app->bind(\App\Interfaces\DeviceRepository::class, \App\Repositories\DeviceRepositoryEloquent::class);
        $this->app->bind(\App\Interfaces\ReleaseRepository::class, \App\Repositories\ReleaseRepositoryEloquent::class);
        $this->app->bind(\App\Interfaces\DevicePushTokenRepository::class, \App\Repositories\DevicePushTokenRepositoryEloquent::class);
        $this->app->bind(\App\Interfaces\TestRepository::class, \App\Repositories\TestRepositoryEloquent::class);
        $this->app->bind(\App\Interfaces\UserRepository::class, \App\Repositories\UserRepositoryEloquent::class);
        $this->app->bind(\App\Interfaces\RoleRepository::class, \App\Repositories\RoleRepositoryEloquent::class);
        $this->app->bind(\App\Interfaces\PermissionRepository::class, \App\Repositories\PermissionRepositoryEloquent::class);
        $this->app->bind(\App\Interfaces\PushNotificationsRepository::class, \App\Repositories\PushNotificationsRepositoryEloquent::class);
        $this->app->bind(\App\Interfaces\AttachmentsRepository::class, \App\Repositories\AttachmentsRepositoryEloquent::class);
        //:end-bindings:
    }
}
