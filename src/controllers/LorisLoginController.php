<?php

class LorisLogin extends BaseController {

    private $success_messages = array(
        'newSProfile' => 'NewProfile added successfully',
    );
    private $error_messages = array(
        'notFound' => 'Aparently the social network profile that you tried to
        access with is not in our database, please login using your local 
        account or previosly synced social profile.',
        'incorrectCred' => 'Incorrect username or password please try again',
        'Found' => 'Try to click on a social network button to login or signup',
        'registered' => 'This social Profile is already linked with your account',
        'registeredToOther' => 'This social Profile is already associated with  an other',
    );

    /**
     * This function must be exclusively used when creating a local account
     * using a social profile, it will generate and return an unused 
     * username for a user.
     * 
     * @param string $username
     * @return string $username
     */
    private static function nameUser($username) {
        $num = 00;
        $dup = NULL;
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
        return $username;
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
        try {
            $userProfile = $this->loginSocial();
            $profile = SocialProfile::find($userProfile->identifier);
            if ($profile != NULL) {
                $user = $profile->user()->getResults();
                Auth::login($user);
                return Redirect::route('admin-user-landing');
            } else {
                echo $this->error_messages['notFound'];
                return Redirect::route('signup')
                                ->with('message', $this->error_messages['notFound']
                );
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

    /**
     * Check if Social Profile Exists in DB if not it will create a new local 
     * account and a new Social Profile and Link them togueter.
     * 
     * @return Redirect or Exeption
     */
    public function signupWithSocial() {
        try {
            $userProfile = $this->loginSocial();
            $profile = SocialProfile::find($userProfile->identifier);
            echo var_dump($profile);
            if ($profile == NULL) {
                //Generate username and password
                $username = $this->nameUser($userProfile->displayName);
                $password = Str::random(8);
                //Create new User
                $user = new User;
                $user->username = $username;
                $user->password = Hash::make($password);
                $user->save();
                //generate new socialProfile
                $sprofile = new SocialProfile;
                $sprofile->identifier = $userProfile->identifier;
                $sprofile->provider = Input::get('network');
                $sprofile->user_id = $user->id;
                $sprofile->save();
                //login
                Auth::login($user);
                //redirect back to admin 
                //include message about changing password
                return Redirect::action('LorisLogin@addLocalProfile', array('network' => Input::get('network')));
            } else {
                return Redirect::action("LorisLogin@loginWithSocial", array('network' => Input::get('network')));
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * 
     * Creates or Updates local Profile
     * 
     * @return \Exception or Redirect
     */
    public function addLocalProfile() {
        try {
            $user = Auth::user();
            //user profile
            $up = $this->loginSocial();

            //try to get a local profile
            $localProfile = Auth::user()->localProfile()->getResults();
            //if it does not exist create a new one
            if (!$localProfile) {
                $localProfile = new LocalProfile;
            }
            //fill/update
            $localProfile->first_name = $up->firstName;
            $localProfile->last_name = $up->lastName;
            $localProfile->description = $up->description;
            $localProfile->gender = $up->gender;
            $localProfile->photo_url = $up->photoURL;
            $localProfile->birth_day = $up->birthDay;
            $localProfile->birth_month = $up->birthMonth;
            $localProfile->birth_year = $up->birthYear;
            $localProfile->email = $up->email;
            $localProfile->address = $up->address;
            $localProfile->country = $up->country;
            $localProfile->city = $up->city;
            $localProfile->zip = $up->zip;
            $localProfile->user_id = $user->id;

            //save profile
            $localProfile->save();
            //link to user
            $user->local_profile_id = $localProfile->id;
            //save User
            $user->save();
        } catch (Exception $e) {
            return $e;
        }
        return Redirect::route('admin-user-landing');
    }

    /**
     * associates a new Social Profile to a user account
     * 
     * @return Void
     */
    public function linkSocialProfile() {
        try {
            //get user
            $user = Auth::user();
            //get social Profile
            $socialProfile = $this->loginSocial();
            //check if Profile is registered already
            $dbsp = SocialProfile::find((int) $socialProfile->identifier);
            if ($dbsp != NULL) {
                if ($dbsp->user()->getResults() == $user) {
                    return Redirect::route('admin-user-landing')
                                    ->with('message', $this->error_messages['registered']);
                } else {
                    return Redirect::route('admin-user-landing')
                                    ->with('message', $this->error_messages['registeredToOther']);
                }
            }
            //Add social profile to DB 
            $user->socialProfiles()->create(array(
                'provider' => Input::get('network'),
                'identifier' => $socialProfile->identifier,
                'user_id' => $user->id,
            ));
            return Redirect::route('admin-user-landing')
                            ->with('message', $this->success_messages['newSProfile'])
                            ->with('sucess', true);
        } catch (Exception $e) {
            return $e;
        }
    }

    public function fillLocalProfile() {
        
    }

}
