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
return View::make('LorisLogin::signup');
}));


Route::group(array('before' => 'auth'), function() {
    Route::get('logout', array('as' => 'logout', function() {
    Auth::logout();
    return Redirect::route('landing');
}));
    Route::group(array('prefix' => 'admin/users'), function() {
        Route::get('/', array('as' => 'admin-users-landing', function() {
        echo "admin users landing";
        foreach (Auth::user()->socialProfiles()->getResults() as $a) {
            var_dump($a);
        }
    }));
    });
});
route::get('testing', function() {
    $username = "carlos.glvn";
    $num = 00;
    $dup = NULL;
    var_dump(User::where('username', '=', $username)->get()->isEmpty());

    //is username cannot be saved the username is taked by other user
    while (!User::where('username', '=', $username)->get()->isEmpty()) {
        //TRy to take the last duplicate of the number
        $dup = User::where('username', 'LIKE', $username . '-sL__')
                ->orderBy('username', 'desc')
                ->first();
        //if theres a hit the the number and increase it
        if ($dup != NULL) {
            $num = (int) substr($dup->username, -2);
            $num++;
        }
        //try a new username
        $username = $username . '-sL' . sprintf('%02d', $num);
    }
    $user = new User;
    $user->username = $username;
    $user->save();

    echo '<br>';









    for ($i = 0; $i < 1000; $i ++) {
        echo sprintf('%03d', $i) . '<br>';
    }
});
Route::get('sociallogin', array('as' => 'socialLogin', 'uses' => 'LorisLogin@loginWithSocial'));
Route::post('locallogin', array('as' => 'localLogin', 'uses' => 'LorisLogin@loginWithLocal'));

Route::post('localsignup', array('as' => 'localSignup', 'uses' => 'LorisLogin@signupWithLocal'));
Route::get('socialsignup', array('as' => 'socialSignup', 'uses' => 'LorisLogin@signupWithLocal'));
