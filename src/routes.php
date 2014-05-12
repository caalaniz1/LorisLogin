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

function showview($view_name) {

    $layout = View::make('LorisLogin::blueprint');
    return $layout->nest('content', 'LorisLogin::' . $view_name);
}

Route::group(array('prefix' => 'users'), function() {

    Route::get('socialConnection', array('as' => 'sociallogin',
        'uses' => 'SLogin@loginUser'));

    Route::get('/', array('as' => 'start', function() {
    $conected = (Auth::check()) ? 'Online' : 'Offline';

    $content = "<h1>" . $conected . "</h1>";


    return View::make('LorisLogin::blueprint', array('content' => $content));
}));


    Route::group(array('before' => 'auth'), function() {

        //display edit form
        Route::get('edit', array('as' => 'edit-profile', function() {
        return showview('forms/profile');
    }));

        Route::get('dashboard', array('as' => 'dashboard', function() {

        return showview('admin/dashboard');
    }));

        Route::get('addProvider', array('as' => 'addProvider',
            'uses' => 'SLogin@addProvider'));

        Route::get('testAPI', array('as' => 'tests', function() {
        //Get current Route
        $route_name = Route::currentRouteName();
        //Get configuration File
        $_config = include app_path() . '/config/hybridauth.php';
        $_config['base_url'] = route($route_name) . '?action=auth';
        //GetObject
        $hybridAuth = new Hybrid_Auth($_config);

        print_r($hybridAuth->getProviders());
        echo '<br><br><br>';
        print_r($hybridAuth->getSessionData());
        echo '<br><br><br>';
        echo "<pre>";
        print_r($hybridAuth->getAdapter(Input::get('p'))->getUserProfile());
        echo "</pre>";
        echo "<pre>";
        print_r($hybridAuth->getConnectedProviders());
        echo "</pre>";




        print_r($hybridAuth->getAdapter(Input::get('p'))->isUserConnected());
        echo "</pre>";

        echo "<pre>";
        $facebook = $hybridAuth->getAdapter('facebook')->api();
        $fb = $facebook->api('me/inbox');
        echo $fb['message'];
        echo "</pre>";
    }));

        //log out action
        Route::get('logout', array('as' => 'logout-user',
            'uses' => 'SLogin@logout'));

        //Form Actions
        Route::group(array('before' => 'crsf'), function() {

            Route::post('edit', array('as' => 'edit-profile-action',
                'uses' => 'SLogin@registerLocalProfile'));

            Route::post('login', array('as' => 'login-user-action',
                'uses' => 'SLogin@registerLocalProfile'));
        });
    });


    Route::group(array('before' => 'guest'), function() {

        //display regsiter form
        Route::get('register', array('as' => 'register-user', function() {
        return showview('forms/register');
    }));

        //display login form
        Route::get('login', array('as' => 'login-user', function() {
        return showview('forms/login');
    }));

        Route::get('loginWithSocial', array(
            'as' => 'login-user-social', 'uses' => 'SLogin@loginUser'));

        Route::group(array('before' => 'crsf'), function() {
            Route::post('login', array('as' => 'login-user-action',
                'uses' => 'SLogin@login'));
            Route::post('register', array('as' => 'registerlocal',
                'uses' => 'SLogin@registerUserLocal'));
        });
    });
});
















Route::get('test', array('as' => 'test', function() {
Auth::login(User::find(1));
$layout = View::make('LorisLogin::blueprint');
return $layout->nest('content', 'LorisLogin::forms/profile');
}));


Route::post('test', array('as' => 'test', 'before' => 'csrf', function() {
var_dump(Input::all());
}));

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
            //echo var_dump($e);
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
    echo "<pre>";
    print_r($a);
    echo "</pre>";
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
    $a->getAdapter('facebook')->setUserStatus(Str::random(256));
    // echo "<pre> I think It worked..."; print_r($a->getAdapter('facebook')->setUserStatus("oOoLlA SsOOii PEdRiTToO Y SsOY MoXxOo!!! JjEE")); echo "</pre>";
    $a->logoutAllProviders();
}
}));
