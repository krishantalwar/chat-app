<?php

use Illuminate\Support\Facades\Route;

use App\Events\NewMessageEvent;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['namespace' => "Auth"], function () {
    Route::get('/', 'LoginController@index')->name("home");
    Route::post('weblogin', 'LoginController@weblogin')->name('login');
    Route::get('register', 'RegisterController@index')->name('register');
    Route::post('webregister', 'RegisterController@register')->name('user.register');;

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('logout', 'LoginController@logout')->name('logout');
    });
});


Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::post('/get_user_chat', 'FirebaseController@loadUserChatViewDynamicChatsection')->name('get_user_chat');
    Route::post('/edit_user_chat', 'FirebaseController@update_chat')->name('edit_user_chat');
});