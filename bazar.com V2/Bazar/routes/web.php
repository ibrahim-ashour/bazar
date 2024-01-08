<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('CatalogServer/create', 'CatalogServer@create');

$router->get('CatalogServer/all', 'CatalogServer@all'); // for test

$router->get('CatalogServer/search/{topic}', 'CatalogServer@search');

$router->get('CatalogServer/info/{id}', 'CatalogServer@info');

$router->put('CatalogServer/update/{id}/{type}/{value}', 'CatalogServer@update');
