<?php

class LorisLogin extends BaseController {

    private $error_messages = array(
        'notFound' => 'Aparently the social network profile that you tried to
        access with is not in our database, please login using your local 
        account or previosly synced social profile.',
        'incorrectCred' => 'Incorrect username or password please try again',
    );
    /**
     * 
     * @param string $username
     * @return \User
     */
    private static function nameUser($username) {
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
        return $user;
    }

    /**
     * Tries to connect to a social network provider is success return
     * the profile else trow expection
     * 
     * @param string $network
     * Network to connect to ex. 'facebook', 'twitter'
     * @return HybridAuth profile object
     */
    public static function loginSocial($network = NULL) {
        //config file
        $_config = include app_path() . '/config/hybridauth.php';
        //Get current Route
        $route_name = Route::currentRouteName();
        $action = Input::get('action');

        // check URL segment
        if ($action == "auth") {
            // process authentication
            try {
                Hybrid_Endpoint::process();
            } catch (Exception $e) {
                // redirect back to http://URL/social/
                return Redirect::route($route_name);
            }
            return;
        }
        try {
            $network = Input::get('network');
            // create a HybridAuth object
            $_config['base_url'] = route($route_name) . '?action=auth';
            $socialAuth = new Hybrid_Auth($_config);
            // authenticate with Google
            $provider = $socialAuth->authenticate($network);
            // fetch user profile
            $userProfile = $provider->getUserProfile();
        } catch (Exception $e) {
            // exception codes can be found on HybBridAuth's web site
            echo $e->getMessage();
            die();
        }

        $provider->logout();
        return $userProfile;
    }

    /**
     * Try to log in using a social profile, if is not found on the DB
     * It redirects to Sigup 
     * 
     * @array Mixed
     * 
     */
    public function loginWithSocial() {
        try {
            $userProfile = $this->loginSocial();
            $profile = SocialProfile::find($userProfile->identifier);
            if ($profile) {
                $user = $profile->user()->getResults();
                Auth::login($user);
                return Redirect::route('admin-users-landing');
            } else {
                echo $this->error_messages['notFound'];
                return Redirect::route('signup')
                                ->with('message', $this->error_messages['notFound']);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Try to Authenticate a user using local credentials
     * 
     * @return Redirect
     */
    public function loginWithLocal() {
        $username = Input::get('username');
        $password = Input::get('password');
        if (Auth::attempt(array('username' => $username, 'password' => $password))) {
            return Redirect::route('admin-users-landing');
        }
        return Redirect::route('login')
                        ->with('message', $this->error_messages['incorrectCred']);
    }

    public function signupWithSocial() {
        try {
            $userProfile = $this->loginSocial();
            $profile = SocialProfile::find($userProfile->identifier);
            if (!$profile) {
                $newUser = new User();
                $newUser->username = $userProfile->username;
                $newUser->password = Hash::make(Str::random(8));
                $i = 0;
                while (!$newUser->save()) {
                    $newUser->usernam = $userProfile->username . $i;
                    $i++;
                }
                $user = $profile->user()->getResults();
                Auth::login($user);
                return Redirect::route('admin-users-landing');
            } else {
                return Redirect::route('signup')
                                ->with('message', $this->error_messages['Found']);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}
