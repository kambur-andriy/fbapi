<?php

/**
 * Main Page
 */
Route::get('/', 'IndexController@main');
Route::post('contact-us', 'IndexController@contactUs');
//Route::get('reset-password', 'IndexController@resetPassword');


/**
 * Account
 */
Route::group(['prefix' => 'account'], function () {
//    Route::post('register', 'AccountController@register');
    Route::post('login', 'AccountController@login');
    Route::post('logout', 'AccountController@logout');
//    Route::post('reset-password', 'AccountController@resetPassword');
//    Route::post('save-password', 'AccountController@savePassword');

    Route::get('fb', 'AccountController@toFacebookProvider');
    Route::get('fb/callback', 'AccountController@FacebookProviderCallback');
});


/**
 * USER
 */
Route::group(['prefix' => 'user'], function () {
    Route::get('profile', 'UserController@profile');
    Route::post('profile', 'UserController@saveProfile');
    Route::post('account', 'UserController@saveAccount');

    Route::get('campaigns', 'UserController@campaigns');
    Route::post('campaign', 'UserController@createCampaign');

    Route::get('sets', 'UserController@sets');
    Route::post('set', 'UserController@createSet');

});


/**
 * ADMIN
 */
Route::group(['prefix' => 'admin'], function () {
    Route::get('users', 'AdminController@users');
});

//Route::get('/test-email', function () {
//    return new App\Mail\ResetPassword('1234567890');
//});
