<?php

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



Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function () {

    Route::get('/', 'AdminController@index')->name('admin.dashboard');
    Route::get('/error', 'AdminController@error')->name('admin.error');
    Route::get('change_lang', 'AjaxController@change_lang')->name('ajax.change_lang');
    Route::post('delete_image', 'AjaxController@deleteImage');
    Route::get('get_locations/{id}', 'AjaxController@getLocations');
    Route::get('get_categories/{id}', 'AjaxController@getCategories');
    
    
    Route::get('profile', 'ProfileController@index')->name('admin.profile');
    Route::patch('profile', 'ProfileController@update');
    Route::get('profile/change_password', 'ProfileController@showChangePasswordForm')->name('admin.profile.change_password');
    Route::patch('profile/change_password', 'ProfileController@updatePassword');

    Route::resource('groups', 'GroupsController');
    Route::post('groups/data', 'GroupsController@data');

    Route::resource('admins', 'AdminsController');
    Route::post('admins/data', 'AdminsController@data');
    Route::post('admins/status/{id}', 'AdminsController@active');

    Route::get('settings', 'SettingsController@index')->name('settings.index');
    Route::post('settings', 'SettingsController@store');

    Route::resource('contact_messages', 'ContactMessagesController');
    Route::delete('contact_messages', 'ContactMessagesController@destroy');
    Route::post('contact_messages/data', 'ContactMessagesController@data');

    Route::resource('categories', 'CategoriesController');
    Route::post('categories/data', 'CategoriesController@data');

    Route::resource('locations', 'LocationsController');
    Route::post('locations/data', 'LocationsController@data');

    Route::resource('packages', 'PackagesController');
    Route::post('packages/data', 'PackagesController@data');

    Route::resource('banners', 'BannersController');
    Route::post('banners/data', 'BannersController@data');

    Route::resource('welcome_screens', 'WelcomeScreensController');
    Route::post('welcome_screens/data', 'WelcomeScreensController@data');

    Route::resource('clients', 'ClientsController');
    Route::post('clients/data', 'ClientsController@data');

    Route::resource('companies', 'CompaniesController');
    Route::post('companies/data', 'CompaniesController@data');

    Route::resource('posts', 'PostsController');
    Route::post('posts/data', 'PostsController@data');

    Route::resource('likes', 'LikesController');
    Route::post('likes/data', 'LikesController@data');

    Route::resource('abuses', 'AbusesController');
    Route::post('abuses/data', 'AbusesController@data');

    Route::resource('comments', 'CommentsController');
    Route::post('comments/data', 'CommentsController@data');

    Route::resource('rates', 'RatesController');
    Route::post('rates/data', 'RatesController@data');

    Route::resource('package_subscriptions', 'PackageSubscriptionsController');
    Route::post('package_subscriptions/data', 'PackageSubscriptionsController@data');

    Route::resource('notifications', 'NotificationsController');
    Route::post('notifications/data', 'NotificationsController@data');

    
    

    Route::group(['namespace' => 'Auth'], function () {
        Route::get('login', 'LoginController@showLoginForm')->name('admin.login');
        Route::post('login', 'LoginController@login')->name('admin.login.submit');
        Route::get('logout', 'LoginController@logout')->name('admin.logout');
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
        Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('admin.password.reset');
        Route::post('password/reset', 'ResetPasswordController@reset')->name('admin.password.update');
    });
});

Route::get('', 'Admin\AdminController@index');
