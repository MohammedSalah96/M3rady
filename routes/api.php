<?php
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

  Route::group(['namespace' => 'Auth'], function () {
    Route::post('register', 'RegisterController@register');
    Route::post('login', 'LoginController@login');
    Route::post('social_login', 'LoginController@socialLogin');

    Route::post('password/code', 'PasswordController@sendResetCode');
    Route::post('password/reset', 'PasswordController@resetPassword');
  });

  Route::get('get_token', 'UserController@getToken');
  Route::get('config', 'BasicController@getConfig');
  Route::get('packages', 'BasicController@packages');
  Route::get('home', 'BasicController@home');
  Route::post('contact_message', 'BasicController@contactMessage');
  Route::get('welcome_screens', 'BasicController@welcomeScreens');

  Route::get('posts/{id}', 'PostsController@show');
  Route::get('posts', 'PostsController@index');

  Route::get('comments', 'CommentsController@index');
  Route::get('rates', 'RatesController@index');

  Route::get('companies', 'CompaniesController@index');
  Route::get('companies/{id}', 'CompaniesController@show');

  Route::group(['middleware' => 'jwt.auth'], function () {
   
    Route::get('profile', 'UserController@getUser');
    Route::put('profile', 'UserController@updateUser');
    Route::post('logout','UserController@logout');
    Route::post('update_lang','UserController@updateLang');
    Route::post('follow/{id}', 'UserController@handleFollow');
    Route::get('followings', 'UserController@getFollowings');
    Route::get('followers', 'UserController@getFollowers');

    Route::post('posts', 'PostsController@store');
    Route::put('posts/{id}', 'PostsController@update');
    Route::delete('posts/{id}', 'PostsController@destroy');
    Route::delete('posts/images/{id}', 'PostsController@deleteImage');
    Route::post('posts/like/{id}','PostsController@handleLike');
    Route::post('posts/abuse/{id}', 'PostsController@abuse');

    
    Route::post('comments','CommentsController@store');
    Route::delete('comments/{id}', 'CommentsController@destroy');

    Route::post('rates', 'RatesController@store');
    Route::delete('rates/{id}', 'RatesController@destroy');

    Route::post('subscribe','BasicController@subscribe');

    Route::resource('price_requests', 'PriceRequestsController');

  });
 
});
