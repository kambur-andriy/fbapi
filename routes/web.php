<?php

/**
 * Main Page
 */
Route::get('/', 'IndexController@main');
Route::get('creative', 'IndexController@creative');
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
    Route::post('campaign/delete', 'UserController@deleteCampaign');

    Route::get('sets', 'UserController@sets');
    Route::post('set', 'UserController@createSet');
    Route::post('set/delete', 'UserController@deleteSet');

    Route::get('creatives', 'UserController@creatives');
    Route::post('creative', 'UserController@createCreative');

    Route::get('ads', 'UserController@ads');
    Route::post('ad', 'UserController@createAd');
    Route::post('ad/delete', 'UserController@deleteAd');

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
