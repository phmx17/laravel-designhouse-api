<?php
use Illuminate\Support\Facades\Route;

// Public Routes
  Route::get('me', 'User\MeController@getMe');
// Get Designs
  Route::get('designs', 'Designs\DesignController@index');
  Route::get('users', 'User\UserController@index');



// route group for authenticated users only
  Route::group(['middleware' => ['auth:api']], function() {
  Route::post('logout', 'Auth\LoginController@logout');  // this must be in this route group!
  Route::put('settings/profile', 'User\SettingsController@updateProfile');
  Route::put('settings/password', 'User\SettingsController@updatePassword');
  // upload designs
  Route::post('designs', 'Designs\UploadController@upload');
  Route::put('designs/{id}', 'Designs\DesignController@update');
  Route::delete('designs/{id}', 'Designs\DesignController@destroy');

});

// route group for guests only
// install the auth routes:
// $ composer require laravel/ui
// $ php artisan ui:controllers
  Route::group(['middleware' => ['guest:api']], function() {
  Route::post('register', 'Auth\RegisterController@register');  // use register()
  Route::post('verification/verify/{user}', 'Auth\VerificationController@verify')->name('verification.verify');  // use verify()
  Route::post('verification/resend', 'Auth\VerificationController@resend');  // use resend()
  Route::post('login', 'Auth\LoginController@login');  // another laravel base controller; don't forget 'Auth\'
  Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
  Route::post('password/reset', 'Auth\ResetPasswordController@reset');
});



















  Route::get('/', function() {
    return response()->json(['message' => 'Hello Laravel World!'], 200);
  });