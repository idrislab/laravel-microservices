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

Route::group(['prefix' => getenv('API_VERSION')], function () {
    Route::post('/ads', 'AdsController@store');
    Route::put('/ads/{id}', 'AdsController@update');
    Route::delete('/ads/{id}', 'AdsController@destroy');
    Route::match(['get', 'head'], '/ads/{id}', 'AdsController@show');
});



