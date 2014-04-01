<?php


class LorisLogin extends BaseController {

    public function doSocialLogin($provider = "facebook") {
        $login = $this->tryLogin($provider);
        //Catch exption
        if ($login["status"] == false) {
            return $login["object"];
        }
        //Look for user in local dataBase;
        $userProfile = $login["object"];
        //Look for a exiting profile
        $socialProfile = SocialProfile::find($userProfile->identifier);
        //if profile exitsts
        if ($socialProfile) {
            //find user associated with it
            $user = User::find($socialProfile->user_id);
            //Log in User
            Auth::login($user);
            return View::make("LorisLogin::landing");
        } else {
            //if no social profile found, try to create one.
            return $this->createAccountWSocial($userProfile, $provider);
        }
    }

    
    
    
    
    
    
    
    /**
     * Try to perfom logIn if successful return array pos 0 = true, pos 1 
     * userProfile object
     * 
     * else return false pos 0 = false, pos 1 string w/ error msg
     * 
     * @return array Mixed
     */
    private function tryLogin($provider) {
        $response = array();
        try {
            // create a HybridAuth object
            $socialAuth = new Hybrid_Auth(app_path() . '/config/hybridauth.php');
            // authenticate with Google
            $provider = $socialAuth->authenticate($provider);
            // fetch user profile
            $userProfile = $provider->getUserProfile();

            $response["status"] = true;
            $response["object"] = $userProfile;
        } catch (Exception $e) {
            // exception codes can be found on HybBridAuth's web site
            $response["status"] = false;
            $response["object"] = $e->getMessage();
        }
        return $response;
    }

    
    
    private function createAccountWSocial($socialProfile, $provider) {
        try {
            $password = str_random(6);
            $user = User::create(
                            array(
                                'username' => $socialProfile->username,
                                'password' => Hash::make($password)
            ));
            //var_dump($socialProfile); die();
            $newSocial = SocialProfile::create(array(
                        'provider' => $provider,
                        'identifier' => $socialProfile->identifier,
                        'user_id' => $user->id,
            ));
            //return View::make()->with(array($socialProfile->username, $password));
            echo $password;
        } catch (Exception $e) {
            echo $e;
        }
    }

}

;
