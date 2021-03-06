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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('login', array('uses' => 'AuthenticateController@authenticate'));

Route::group(['middleware' => ['jwt.auth']], function () {
    /**
     * Users routes
     */
    Route::resource('users', 'UserController', ['except' => [ 'create', 'edit' ]]);

    /**
     * Items routes
     */
    Route::get('items', 'ItemController@index');

    /**
     * Settles routes
     */
    Route::resource('settles', 'SettleController', ['except' => [ 'create', 'edit', 'delete' ]]);

    /**
     * Slips routes
     */
    Route::get('/slips/{slip}/items', 'SlipController@items');
    Route::post('/slips/{slip}/items/{item}', 'SlipController@itemsAdd');
    Route::delete('/slips/{slip}/items/{item}', 'SlipController@itemsRemove');
    Route::resource('slips', 'SlipController', ['except' => [ 'create', 'edit' ]]);
});
