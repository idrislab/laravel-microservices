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
    Route::post('/conversation', 'ConversationController@store');
    Route::delete('/conversation/{id}', 'ConversationController@destroy');
    Route::match(['get', 'head'], '/conversation/{id}', 'ConversationController@show');
    Route::match(['get', 'head'], '/conversation', 'ConversationController@index');

    Route::post('/conversation/{conversationId}/message', 'MessageController@store');
    Route::put('/conversation/{conversationId}/message/{messageId}', 'MessageController@update');
    Route::delete('/conversation/{conversationId}/message/{messageId}', 'MessageController@destroy');
    Route::match(['get', 'head'], '/conversation/{conversationId}/message/{messageId}',
        'MessageController@show');
});


