<?php

class User extends BaseController {

    /**
     * Try to perfom logIn if successful return array pos 0 = true, pos 1 
     * userProfile object
     * 
     * else return false pos 0 = false, pos 1 string w/ error msg
     * 
     * @array Mixed
     */
    private function socialLogin($provider) {
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

    /**
     * Try to log in using a social profile, if is not found on the DB
     * It redirects to Sigup 
     * 
     * @array Mixed
     * 
     */
    public function loginWithSocial() {
        //variable get through $_POST
        //@var string
        $_provider = Input::get('provider');

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
            if($socialProfile){
                $user = $socialProfile->user();
                Auth::login($user);
                Redirect::route('admin-users-landing');
                
            }else{
                $message = array(
                    "Please register in oder to be able to add your profile"
                    );
                Redirect::route('signup')->whith('message', $message);
            }
        }else{
            //If status return false object is a string with errors
            $errors = $userProfile['object'];
            
            $message = array($errors);
            Redirect::route('error')->with('message',$message);
        }
    }

}

;
