<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/activities/list','ActivitiesController@list');
Route::get('/activities/getActivity/{id}', 'ActivitiesController@getActivity');
Route::get('/activities/getFormtags', 'ActivitiesController@getFormtags');
Route::post('/images/uploadImgFileApi','ImageController@uploadImgFileApi');
Route::post('activities/storeActivity', 'ActivitiesController@storeActivity');
Route::get('/enrollinfos/getEnrollinfos/{activityid}', 'EnrollinfoController@getEnrollinfos');
Route::get('/enrollinfos/updateReadflag/{id}', 'EnrollinfoController@updateReadflag');
