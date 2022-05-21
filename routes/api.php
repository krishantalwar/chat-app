<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Route::get('unauthorized',function () { 
//     return response()->json([
//         'message' => 'Unauthorized',     
//     ], 401);
// })->name('unauthorized');

Route::group(['prefix' => 'auth', 'namespace' => "Auth"], function () {
    Route::post('login', 'LoginController@login');
    Route::post('register', 'RegisterController@register');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', 'AuthController@logout');
    });
});


Route::group(['middleware' =>   ['auth:api'], 'namespace' => "Auth"], function ($router) {
    Route::post('/profile', 'LoginController@profile');
    Route::post('findroomid', 'FirebaseController@FindChatRoomIdForApi');
    Route::post('createdChatRoomID/{room_id}?/{user_id}?', 'FirebaseController@createdChatRoomID');
    Route::post('sendchat/{room_id?}/{messageText?}/{data?}', 'FirebaseController@updateChatInFireStore');
});