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
    $router->get('/', function () use ($router) {
        return $router->app->version();
    });
    //Crear clientes
    $router->post('/usuarios', 'UserController@create');
    //Consultar clientes
    $router->get('/usuarios', 'UserController@index');
    //Consulta un cliente by Id
    $router->get('/usuarios/{id}', 'UserController@getUser');
    //Actualizar Cliente
    $router->put('/usuarios/{id}', 'UserController@update');
    $router->patch('/usuarios/{id}/status', ['uses' => 'UserController@updateStatus']);
    //Eliminar Cliente
    $router->delete('/usuarios/{id}', 'UserController@delete');

