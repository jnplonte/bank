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

Route::group(['middleware' => ['bankApi']], function () {

    Route::get('/', function () {
        return abort(404);
    });

    Route::get('users', [
        'as' => 'user', 'uses' => 'UserController@getAll'
    ]);

    Route::post('user', [
        'as' => 'user', 'uses' => 'UserController@insert'
    ]);

    Route::get('user/{id}', [
        'as' => 'user', 'uses' => 'UserController@get'
    ])->where('id', '[0-9]+');

    Route::match(['put', 'delete'], 'user/{id}', [
        'as' => 'user', 'uses' => 'UserController@update'
    ])->where('id', '[0-9]+');
});
