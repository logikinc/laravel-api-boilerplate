<?php

use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

$api->version(['v1'], function (Router $api) {

    /*
    |--------------------------------------------------------------------------
    | Authentication routes http://{domain}/api/auth/
    |--------------------------------------------------------------------------
    | These routes are used authentication and will work with V1 and V2
    */
    $api->group(['prefix' => 'auth'], function(Router $api) {

        // Authentication Routes
        $api->post('login', 'App\Http\Api\Auth\LoginController@login'); // Logs a user in
        $api->post('recovery', 'App\Http\Api\Auth\ForgotPasswordController@sendResetEmail'); // Sends the user an email to reset their password
        $api->post('reset', 'App\Http\Api\Auth\ResetPasswordController@resetPassword'); // Resets a users password
    });

    /*
    |--------------------------------------------------------------------------
    | Version 1 http://{domain}/api/v1/
    |--------------------------------------------------------------------------
    | These routes are used for the second version of the APP
    | These will used with the ondash implementation
    */
    $api->group(['middleware' => ['api.auth'], 'prefix' => 'v1', 'namespace' => 'App\Http\Api\V1\Controllers'], function(Router $api) {

        // User Settings
        $api->post('user/change_password', 'UserSettingsController@changeCurrentUserPassword'); // Change current user password
        $api->post('user/update', 'UserSettingsController@updateCurrentUser'); // Update current user details
        $api->get('user', 'UserSettingsController@showCurrentUser'); // Get current user profile

        // Roles
        $api->get('roles', 'RolesController@index'); // Get all roles
        $api->post('roles', 'RolesController@store'); // Create a role
        $api->get('roles/{id}', 'RolesController@show'); // Get single role
        $api->post('roles/{id}', 'RolesController@update'); // Update a role
        $api->post('roles/{id}/assign_permission/{permission_id}', 'RolesController@assignPermissionToRole'); // Assign Permission to role
        $api->post('roles/{id}/assign/{user_id}', 'RolesController@assignRoleToUser'); // Assign a role to a user
        $api->post('roles/{id}/remove/{user_id}', 'RolesController@removeRoleFromUser'); // Revoke a role from a user

        // Permissions
        $api->get('permissions', 'PermissionsController@index'); // Get all permissions
        $api->post('permissions', 'PermissionsController@store'); // Create a permission
        $api->get('permissions/{id}', 'PermissionsController@show'); // Get single permission
        $api->post('permissions/{id}', 'PermissionsController@update'); // Update a permission

        // Users
        $api->get('users', 'UsersController@index'); // Get all users
        $api->post('users', 'UsersController@store'); // Create a user
        $api->get('users/{id}', 'UsersController@show'); // Get single user
        $api->post('users/{id}', 'UsersController@update'); // Update a user
        $api->post('users/{id}/delete','UsersController@destroy'); // Delete profile
        $api->post('users/{id}/recover', 'UsersController@restore'); // Recover deleted profile

    });

});
