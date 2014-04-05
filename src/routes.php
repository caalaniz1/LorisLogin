<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the Closure to execute when that URI is requested.
  |
 */

Route::get('/', array('as' => 'landing', function() {
return View::make('LorisLogin::home');
}));

Route::group(array('before' => 'guest'), function() {
    Route::get('login', array('as' => 'login', function() {
    return View::make('LorisLogin::login');
}));
    Route::get('signup', array('as' => 'signup', function() {
    return View::make('LorisLogin::signup');
}));
});


Route::group(array('before' => 'auth'), function() {
    Route::get('logout', array('as' => 'logout', function() {
    Auth::logout();
    return Redirect::route('landing');
}));
    Route::group(array('prefix' => 'admin/users'), function() {

        Route::get('/', array('as' => 'admin-user-landing', function() {
        return View::make('LorisLogin::admin/landing');
    }));
        Route::get('localprofile', array('as' => 'localProfile', function() {
        return View::make('LorisLogin::admin/userProfile');
    }));
    });
    //Restricted Actions
    Route::get('addlocalprofile', array('as' => 'addLocalProfile', 'uses' => 'LorisLogin@addLocalProfile'));
    Route::get('filllocalprofile', array('as' => 'fillLocalProfile', 'uses' => 'LorisLogin@fillLocalProfile'));
    Route::get('linksocialprofile', array('as' => 'linkSocialProfile', 'uses' => 'LorisLogin@linkSocialProfile'));
});



route::get('testing', function() {
    $profile = SocialProfile::find(100002162836132);
    var_dump($profile);
});

//Public Actions
Route::get('sociallogin', array('as' => 'socialLogin', 'uses' => 'LorisLogin@loginWithSocial'));
Route::post('locallogin', array('as' => 'localLogin', 'uses' => 'LorisLogin@loginWithLocal'));

Route::post('localsignup', array('as' => 'localSignup', 'uses' => 'LorisLogin@signupWithLocal'));
Route::get('socialsignup', array('as' => 'socialSignup', 'uses' => 'LorisLogin@signupWithSocial'));
