<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['prefix' => 'oauth2'], function () use ($router) {
    $router->get('login', 'OAuth2Controller@login');
    $router->get('callback', 'OAuth2Controller@loginCallback');
    $router->post('token', 'OAuth2Controller@getToken');
});

$router->group(['middleware' => 'auth', 'prefix' => 'api'], function () use ($router) {
    $router->get('me', 'UserController@get');
});
