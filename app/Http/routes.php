<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    $api->get('hello', 'App\Http\Controllers\Api\HomeController@index');

    $api->post('register', 'App\Http\Controllers\Auth\ApiAuthController@register');

    $api->post('login', 'App\Http\Controllers\Auth\ApiAuthController@authenticate');

    $api->post('logout', 'App\Http\Controllers\Auth\ApiAuthController@logout');


    /*
     * Protected API route with JWT (must be logged in)
     */
    $api->group(['middleware' => 'api.auth'], function ($api) {

        $api->get('authenticated', 'App\Http\Controllers\Api\UsersController@authenticated');

        $api->get('token', 'App\Http\Controllers\Api\UsersController@refreshToken');

    });


});


