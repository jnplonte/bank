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

Route::get('/', function () {
    return 'Think Labs';
});

Route::group(['middleware' => ['bankApi']], function () {
    //getting all users information
    Route::get('users', [
        'as' => 'user', 'uses' => 'UserController@getAll'
    ]);

    //inserting user information
    Route::post('user', [
        'as' => 'user', 'uses' => 'UserController@insert'
    ]);

    //getting specific user information
    Route::get('user/{id}', [
        'as' => 'user', 'uses' => 'UserController@get'
    ])->where('id', '[0-9]+');

    //updating and deleting user information
    Route::match(['put', 'delete'], 'user/{id}', [
        'as' => 'user', 'uses' => 'UserController@update'
    ])->where('id', '[0-9]+');

    //inserting and deleting account
    Route::match(['post', 'delete'], 'account/{id}', [
        'as' => 'user', 'uses' => 'AccountController@manage'
    ])->where('id', '[0-9]+');

    //withdraw money
    Route::match(['put'], 'withdraw/{id}', [
        'as' => 'user', 'uses' => 'AccountController@withdraw'
    ])->where('id', '[0-9]+');

    //deposit money
    Route::match(['put'], 'deposit/{id}', [
        'as' => 'user', 'uses' => 'AccountController@deposit'
    ])->where('id', '[0-9]+');

    //transfer money
    Route::match(['put'], 'transfer/{id}', [
        'as' => 'user', 'uses' => 'AccountController@transfer'
    ])->where('id', '[0-9]+');
});
