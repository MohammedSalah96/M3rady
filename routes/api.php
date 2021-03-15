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

 Route::group(['namespace' => 'Api'], function () {

  Route::get('get_token', 'UserController@getToken');
  Route::get('config', 'BasicController@getConfig');

  Route::get('posts/{id}', 'PostsController@show');
  Route::get('posts', 'PostsController@index');

  Route::get('comments', 'CommentsController@index');

  Route::group(['middleware' => 'jwt.auth'], function () {
   
    Route::get('profile', 'UserController@getUser');
    Route::put('profile', 'UserController@updateUser');
    Route::post('logout','UserController@logout');
    Route::post('update_lang','UserController@updateLang');

    Route::post('posts', 'PostsController@store');
    Route::put('posts/{id}', 'PostsController@update');
    Route::delete('posts/{id}', 'PostsController@destroy');
    Route::delete('posts/images/{id}', 'PostsController@deleteImage');
    Route::post('posts/like/{id}','PostsController@handleLike');

    
    Route::post('comments','CommentsController@store');
    Route::delete('comments/{id}', 'CommentsController@destroy');
    
    
  });
  Route::group(['namespace' => 'Auth'], function () {
    Route::post('register', 'RegisterController@register');
    Route::post('login', 'LoginController@login');
    Route::post('social_login', 'LoginController@socialLogin');

    Route::post('password/code', 'PasswordController@sendResetCode');
    Route::post('password/reset', 'PasswordController@resetPassword');
  });
});
