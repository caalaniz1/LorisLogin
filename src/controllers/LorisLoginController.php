<?php

class LorisLogin extends BaseController {

    public static function loginSocial() {
        $_config = include app_path() . '/config/hybridauth.php';
        $route_name = Route::currentRouteName();

        $action = Input::get('action');
        $network = Input::get('network');
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
            // create a HybridAuth object
            $_config['base_url'] = route($route_name).'?action=auth';
            $socialAuth = new Hybrid_Auth($_config);
            // authenticate with Google
            $provider = $socialAuth->authenticate($network);
            // fetch user profile
            $userProfile = $provider->getUserProfile();
        } catch (Exception $e) {
            // exception codes can be found on HybBridAuth's web site
            return $e->getMessage();
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
    public function loginWithSocial($obj) {
        echo "d";
        echo var_dump($obj);
        die();

        //variable get through $_POST
        //@var string
        $_provider = Input::get('provider');
        echo $_provider;

        //Local private method
        //indexes: [0]['status'] [1]['object']
        //@var Array Mixed bool,obj
        $userProfile = $this->socialLogin($_provider);

        if ($userProfile['status']) {
            //Re-assign to profile object
            $userProfile = $userProfile['object'];
            //Look for users with this profile
            $socialProfile = SocialProfile::find($userProfile->identifier);
            //if $socialProfile existis in DB
            if ($socialProfile) {
                $user = $socialProfile->user();
                echo "<pre>";
                var_dump($userProfile);
                echo "</pre>";
                Auth::login($user);
                Redirect::route('admin-users-landing');
            } else {
                $message = array(
                    "Please register in oder to be able to add your profile"
                );
                Redirect::route('signup')->whith('message', $message);
            }
        } else {
            //If status return false object is a string with errors
            $errors = $userProfile['object'];

            $message = array($errors);
            Redirect::route('error')->with('message', $message);
        }
    }

}

;
