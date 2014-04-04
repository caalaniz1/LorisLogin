<?php

class LorisLogin extends BaseController {

    public static function loginSocial($network = NULL) {
        $_config = include app_path() . '/config/hybridauth.php';
        $route_name = Route::currentRouteName();
        $action = Input::get('action');
        if ($network == NULL) {
            $network = Input::get('network');
        }
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
            $_config['base_url'] = route($route_name) . '?action=auth';
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
    public function loginWithSocial() {
        $userProfile = $this->loginSocial();
        echo '<pre>';
        echo var_dump($userProfile);
        echo '</pre>';
        
    }

}
