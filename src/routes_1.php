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

function blah() {
    //Get current Route
    $route_name = Route::currentRouteName();
    //Get configuration File
    $_config = include app_path() . '/config/hybridauth.php';
    $_config['base_url'] = route($route_name) . '?action=auth';
    //Get variables
    $action = Input::get('action');
    $network = Input::get('network');
    // check URL segment
    if ($action == "auth") {
        // process authentication
        try {
            Hybrid_Endpoint::process();
        } catch (Exception $e) {
            // redirect back to http://URL/social/
            echo var_dump($e);
            return Redirect::route($route_name);
        }
        return;
    } else if ($network == NULL) {
        die("I don't know where to connect too!! Err. 005");
    } else {
        try {
            $hybirdAuth = new Hybrid_Auth($_config);
            //try to authenticate with network
            $hybirdAuth->authenticate($network);
            return $hybirdAuth;
        } catch (Exception $e) {
            //Log it
            die('LOG THIS!!! <br><br>' . var_dump($e));
        }
    }
}

/**
 * Replace white spaces with underscore
 * 
 * @param string $name
 * @return tring
 */
function formatName($name = NULL) {
    return !$name ? NULL : preg_replace('/\s+/', '_', $name);
}

Route::get('test124', array('as' => 'blahs', function() {

var_dump(formatName());
}));
Route::get('test123', array('as' => 'blah', function() {
$a = blah();


if ($a) {
    //
    print_r($a->getConnectedProviders());
    echo "<pre>"; print_r($a); echo "</pre>";
    echo "<br>";
    echo "<pre>";
    print_r($a->getAdapter('facebook')->getUserProfile());
    echo "</pre>";
    echo "<br>";
    //echo "<pre>"; print_r($a->getAdapter('facebook')->getUserContacts()); echo "</pre>";
    echo "<br>";
    echo "<br>";
    // echo "<pre>"; print_r($a->getAdapter('facebook')->getUserActivity("timeline")); echo "</pre>";
    // foreach($a->getAdapter('facebook')->getUserContacts() as $c){
    //   echo "</p>".$c->displayName."<br><img src = ".$c->photoURL."></p>" ;
    // }
    echo "<br>";
    echo "<br>";
    echo "<br>";

    //for($i = 0 ; $i < 100 ; $i ++){
    //    $a->getAdapter('facebook')->setUserStatus(Str::random(256));
    //}
    // $a->getAdapter('facebook')->setUserStatus(Str::random(256));
    // echo "<pre> I think It worked..."; print_r($a->getAdapter('facebook')->setUserStatus("oOoLlA SsOOii PEdRiTToO Y SsOY MoXxOo!!! JjEE")); echo "</pre>";
    $a->logoutAllProviders();
}
}));
Route::get('test234', array('as' => 'socialSignups', 'uses' => 'SLogin@loginUser'));
