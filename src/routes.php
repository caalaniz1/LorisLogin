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

Route::get('login', array('as' => 'login', function() {
    return View::make('LorisLogin::login');
}));





Route::get('signup', array('as' => 'signup', function() {
    echo "signup";
}));


Route::group(array('before' => 'auth'), function() {
    Route::get('logout', array('as' => 'logout', function() {
        var_dump(LorisLogin::loginSocial());
    }));
    Route::group(array('prefix' => 'admin/users'), function() {
        Route::get('/', array('as' => 'admin-users-landing', function() {
            echo "admin users landing";
        }));
    });
});

Route::get('slogin',array('as'=>'socialLogin', 'uses'=>'LorisLogin@loginWithSocial'));











/*

Route::get('logout',array('as' => 'logout', function() {
Auth::logout();
return Redirect::route('landing');
}));
Route::get('/',array('as' => 'landing', function() {
    return View::make('LorisLogin::home');
}));

Route::get('login', function() {

    return View::make('LorisLogin::login');
});

Route::group(array('prefix' => 'lorislogin'), function() {

    Route::group(array('before' => 'auth'), function() {
        Route::get('private', function() {
            return View::make("LorisLogin::private");
            //Auth::logout();
        });
    });
});

Route::get('test', function() {

    return View::make("LorisLogin::blueprint");
});

Route::get('test2', 'LorisLogin@doSocialLogin');




Route::get('social/{action?}', array("as" => "hybridauth", function($action = "") {
// check URL segment
if ($action == "auth") {
    // process authentication
    try {
        Hybrid_Endpoint::process();
    } catch (Exception $e) {
        // redirect back to http://URL/social/
        return Redirect::route('hybridauth');
    }
    return;
}
try {
    // create a HybridAuth object
    $socialAuth = new Hybrid_Auth(app_path() . '/config/hybridauth.php');
    // authenticate with Google
    $provider = $socialAuth->authenticate("facebook");
    // fetch user profile
    $userProfile = $provider->getUserProfile();
} catch (Exception $e) {
    // exception codes can be found on HybBridAuth's web site
    return $e->getMessage();
}
// access user profile data
echo "Connected with: <b>{$provider->id}</b><br />";
echo "As: <b>{$userProfile->displayName}</b><br />";
echo "<pre>" . print_r($userProfile, true) . "</pre><br />";

// logout
$provider->logout();
}));
*/