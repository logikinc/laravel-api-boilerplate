<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$this->get('/', 'WelcomeController@index');


$this->group(['namespace' => 'Auth'], function() {
    // Authentication Routes...
    $this->get('/login', 'LoginController@showLoginForm')->name('login');
    $this->post('login', 'LoginController@login')->name('login.post');
    $this->post('logout', 'LoginController@logout')->name('logout');

    // Password Reset Routes...
    $this->get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
    $this->post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    $this->get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset.token');
    $this->post('password/reset', 'ResetPasswordController@reset')->name('password.request');
});

$this->group(['namespace' => 'Admin'], function() {
    $this->get('/home', 'HomeController@index')->name('home');
});

