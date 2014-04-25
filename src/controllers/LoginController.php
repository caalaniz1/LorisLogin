<?php

include ('help.php');

class SLogin extends BaseController {

    /**
     * Uses 2 GET variables, action and network
     * 
     * @param string $network, Network string identifier, to obtain provider
     * @param string action
     * 
     * @return Hybrid_Provider_Adapter Authenticated Hybrid Auth Adapter
     */
    private function socialLogin() {
        if (Input::get('action') == "auth") {
            Hybrid_Endpoint::process();
        }
        try {
            $network = Input::get('network');
            if (!$network) {
                die(LoginHelper::$errorCodes['01']);
            }
            //Get Hybridauth Intance
            $HAuth = LoginHelper:: getHybridAuthObject();

            //try to autenticate with network
            $adapter = $HAuth->authenticate($network);
            return $adapter;
        } catch (Exception $e) {
            switch ($e->getCode()) {
                case 0 : echo "Unspecified error.";
                    break;
                case 1 : echo "Hybriauth configuration error.";
                    break;
                case 2 : echo "Provider not properly configured.";
                    break;
                case 3 : echo "Unknown or disabled provider.";
                    break;
                case 4 : echo "Missing provider application credentials.";
                    break;
                case 5 : echo "Authentification failed. "
                    . "The user has canceled the authentication or the provider refused the connection.";
                    break;
                case 6 : echo "User profile request failed. Most likely the user is not connected "
                    . "to the provider and he should authenticate again.";
                    $adapter->logout();
                    break;
                case 7 : echo "User not connected to the provider.";
                    $adapter->logout();
                    break;
                case 8 : echo "Provider does not support this feature.";
                    break;
            }
        }
    }

    /**
     * Log User into application and retrive its past session if any
     * 
     * @param User $user
     */
    public function login($reRoute = 'dashboard') {
        $input = Input::all();


        $validator = Validator::make(
                        $input, array(
                    'username' => 'required|alpha-dash',
                    'password' => 'required|between:5,15|alpha-dash',
                        )
        );
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->
                            withInput(Input::except('password'));
        }
        //Login User
        $cred = array(
            'username' => $input['username'], 'password' => $input['password']
        );

        if (!Auth::Attempt($cred)) {
            return Redirect::back()->withErrors(array('login'=>'Please try to login again'))->
                            withInput(Input::except('password'));
        }
        $user = Auth::user();
        //Retrive Session if any
        $session = $user->hybridSessions()->getResults();
        if ($session) {
            $HAuth = LoginHelper::getHybridAuthObject();
            $stored_session = unserialize($session->hybridauth_sessions);
            $new_session = unserialize($HAuth->getSessionData());

            $restore_session = array_merge($new_session, $stored_session);
            $HAuth->restoreSessionData(serialize($restore_session));
        }
        return Redirect::route($reRoute);
    }

    /**
     *  
     * Logs User Out the application and save this session
     * 
     * @return string Error message
     */
    public function logout() {
        if (Auth::check()) {
            $user = Auth::user();
            Auth::logout();
            $hybridauth = LoginHelper::getHybridAuthObject();
            $session = $user->hybridSessions()->getResults();
            if (!$session) {
                $session = HybridSessions::create(array(
                            'user_id' => $user->id,
                ));
            }
            $session->saveConnection($hybridauth->getSessionData());
        }
    }

    /**
     * 
     * HTTP Verb : POST
     * 
     * Register user by using it's social profile or by POST from form
     * 
     * @param Hybrid_Provider_Adapter $HAuth
     * @param Int $privileges
     * @return USER 
     */
    public function registerUser($HAuth = NULL, $privileges = 1) {
        $input = Input::all();
        if (!isset($input['network']) && $HAuth == NULL) {
            $password = $input['password'];
            $username = $input['username'];
        } else {
            //Get social info
            $UserProfile = $HAuth->getUserProfile();
            $username = LoginHelper::formatName($UserProfile->displayName);
            $password = NULL;
        }
        /**
         * USER TABLE DETAILS
          CREATE TABLE users (
          id int(10) unsigned NOT NULL AUTO_INCREMENT,
          username varchar(20) COLLATE utf8_unicode_ci NOT NULL,
          `password` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
          `privileges` smallint(6) NOT NULL DEFAULT '0',
          local_profile_id int(10) unsigned DEFAULT NULL,
          created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          PRIMARY KEY (id),
          UNIQUE KEY users_username_unique (username)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
         * 
         */
        $user = User::create(array(
                    'username' => $username,
                    'password' => $password,
                    'privileges' => $privileges,
                    'local_profile_id' => NULL,
        ));
        return $user;
    }

    public function registerLocalProfile() {
        if (!Auth::check()) {
            return NULL;
        }
        $user = Auth::user();
        $input = Input::all();
        $input["birthDay"] = intval($input["birthDay"]);
        $input["birthMonth"] = intval($input["birthMonth"]);
        $input["birthYear"] = intval($input["birthYear"]);
        $validator = Validator::make(
                        $input, array(
                    'firstName' => 'required|alpha',
                    'lastName' => 'required|between:5,15|alpha',
                    'birthDay' => 'integer|between:0,31',
                    'birthMonth' => 'integer|between:0,12',
                    'birthYear' => 'required|integer|between:0,3000',
                    'description' => 'between:15,3000',
                    'gender' => 'alpha|required',
                    'photoUrl' => 'required|image',
                    'email' => 'required|email',
                    'address' => 'required|alpha_dash|max:30'
                        )
        );
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        $input['description'] = e($input['description']);

        $data = array(
            'first_name' => $input['firstName'],
            'last_name' => $input['lastName'],
            'description' => $input['description'],
            'gender' => $input['gender'],
            'photo_URL' => $input['photoUrl'],
            'birth_day' => $input['birthDay'],
            'birth_month' => $input['birthMonth'],
            'birth_year' => $input['birthYear'],
            'email' => $input['email'],
            'address' => $input['address'],
            'country' => NULL,
            'city' => NULL,
            'zip' => NULL,
        );

        $profile = $user->localProfile()->getResults();
        if ($profile) {
            $profile->update($data);
        } else {
            $user->localProfile()->create($data);
        }
    }

    /**
     * Add user to the DB and return that user
     * 
     * @param Hybrid_Provider_Adapter $HAuth
     * @param User $user
     */
    public function registerSocialProfile($HAuth, $user) {
        /**
         * CREATE TABLE social_profiles (
          provider varchar(20) COLLATE utf8_unicode_ci NOT NULL,
          identifier bigint(20) unsigned NOT NULL,
          user_id int(10) unsigned DEFAULT NULL,
          hybridauth_session text COLLATE utf8_unicode_ci NOT NULL,
          created_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          updated_at timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          PRIMARY KEY (identifier)
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
         */
        SocialProfile::create(array(
            'provider' => $HAuth->id,
            'identifier' => $HAuth->getUserProfile()->identifier,
            'user_id' => $user->id,
        ));
    }

    public function sloginUser() {

        $this->socialLogin();
        $user = User::find(1);
        $this->login($user);
        $HAuth = LoginHelper::getHybridAuthObject();
        LoginHelper::printFormatVar($HAuth->getConnectedProviders());
        $this->logout();
        $HAuth->logoutAllProviders();
    }

    /**
     * 
     */
    public function loginUser() {
        //Get Adapter
        $adapter = $this->socialLogin();
        //Get User Profile from Adapter
        //LoginHelper::printFormatVAr($adapter->adapter);
        //echo $adapter->getUserProfile()->identifier;
        $profile = $adapter->getUserProfile();

        //LoginHelper::printFormatVAr($adapter);
        //Look for profile in DB
        $userProfile = SocialProfile::find($profile->identifier);
        //If Found
        if ($userProfile) {
            //Get User
            $user = $userProfile->user()->getResults();

            //Log in User
            $this->login($user);
            echo "logfed in: " . Auth::check() . '<br>';
            LoginHelper::printFormatVAr(LoginHelper::getHybridAuthObject()->getAdapter('twitter'));
        } else {
            echo "Profile Not fount";
            echo "Registering profile..";
            $newUser = $this->registerUser($adapter);
            $this->registerSocialProfile($adapter, $newUser);
        }

        $this->logout();
        echo "loged out: " . Auth::check() . '<br>';
        $adapter->logout();
    }

}

/**
 * Helper functions and variables
 */

