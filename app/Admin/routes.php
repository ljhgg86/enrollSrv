<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->resource('formtag', FormtagController::class);
    $router->get('/inputtype/getInputtypes', 'InputtypeController@getInputtypes');
    $router->resource('inputtype', InputtypeController::class);
    $router->get('activities/myList', 'ActivitiesController@myList')->name('activities.myList');
    $router->get('activities/operate/{id}', 'ActivitiesController@operate')->name('activities.operate');
    $router->resource('activities', ActivitiesController::class);
});
