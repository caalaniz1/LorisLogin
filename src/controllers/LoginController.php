<?php

include ('help.php');
include('rules.php');

class SLogin extends BaseController {

    /**
     * Uses 2 GET variables, action and network
     * 
     * @param string $network, Network string identifier, to obtain provider
     * @param string action
     * 
     * @return Hybrid_Provider_Adapter Authenticated Hybrid Auth Adapter
     */
    public function socialLogin() {
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
     * 
     * @param User $user
     * @param string $reRoute
     * @return type
     */
    public function login($user = null, $reRoute = 'dashboard') {
        $input = Input::all();

        if ($user == null) {
            $validator = Validator::make(
                            $input, array(
                        'username' => 'required|alpha-dash',
                        'password' => 'required|between:5,15|alpha-dash',
                            )
            );
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)
                                ->withInput(Input::except('password'));
            }
            //Login User
            $cred = array(
                'username' => $input['username'], 'password' => $input['password']
            );

            if (!Auth::Attempt($cred)) {
                return Redirect::back()
                                ->withErrors(array('login' => 'Please try to login again'))
                                ->withInput(Input::except('password'));
            }
        } else {
            Auth::login($user);
        }
        $user = Auth::user();
        //Retrive Session if any
        $session = $user->hybridSessions()->getResults();
        $this->updateSession($session);
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
            Auth::logout();
            $hybridauth = LoginHelper::getHybridAuthObject();
            $hybridauth->logoutAllProviders();
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

        $user = User::create(array(
                    'username' => $username,
                    'password' => $password,
                    'privileges' => $privileges,
                    'local_profile_id' => NULL,
        ));

        return $user;
    }

    public function registerUserLocal() {
        $input = Input::all();

        $validator = Validator::make(
                        $input, array(
                    'username' => 'required|alpha_dash|unique:users,username',
                    'password' => 'required|same:password-r|alpha_dash',
                    'password-r' => 'required|same:password|alpha_dash',
                    'email' => 'required|email|unique:local_profiles,email',
                        ), $GLOBALS['$validator_messages'] //rules.php
        );
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->
                            withInput(Input::except('password'));
        }
        $data = array(
            'username' => $input['username'],
            'password' => Hash::make($input['password']),
            'privileges' => 1,
        );
        $newUser = User::create($data);
        LocalProfile::create(array("email" => $input['email'], 'user_id' => $newUser->id));
        Auth::login($newUser);
        return Redirect::route('dashboard');
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
                    'address' => 'required|max:30|alpha_spaces',
                        ), $GLOBALS['$validator_messages'] //rules.php
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
            'date_of_birth' =>
            $input['birthYear'] . '-' . $input['birthMonth'] . '-' . $input['birthDay'],
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
        return Redirect::route('dashboard');
    }

    /**
     * Add user to the DB and return that user
     * 
     * @param Hybrid_Provider_Adapter $HAuth
     * @param User $user
     */
    public function registerSocialProfile($HAuth, $user) {

        SocialProfile::create(array(
            'provider' => $HAuth->id,
            'identifier' => $HAuth->getUserProfile()->identifier,
            'user_id' => $user->id,
        ));
    }

    /**
     * 
     * @param HybridSessions $session
     * @return bool
     */
    public function updateSession($session) {

        if (!$session) {
            $session = HybridSessions::create(array(
                        'user_id' => Auth::user()->id,
            ));
        }

        $HAuth = LoginHelper::getHybridAuthObject();
        $stored_session = unserialize($session->hybridauth_sessions);
        $new_session = unserialize($HAuth->getSessionData());


        if (is_array($new_session) && is_array($stored_session)) {
            $restore_session = serialize(
                    array_merge($new_session, $stored_session));
        } else if (is_array($new_session) && !is_array($stored_session)) {
            $restore_session = serialize($new_session);
        } else if (is_array($stored_session) && !is_array($new_session)) {
            $restore_session = serialize($stored_session);
        } else {
            die("something must have gone REALLY bad");
        }



        $HAuth->restoreSessionData($restore_session);
        $session->saveConnection($HAuth->getSessionData());
    }

    public function addProvider() {
        $adapter = $this->socialLogin();

        if ($adapter) {
            if (!SocialProfile::find($adapter->getUserProfile()->identifier)) {
                $this->registerSocialProfile($adapter, Auth::user());
                //Sv session in DB
                $session = Auth::user()->hybridSessions()->getResults();
                $this->updateSession($session);
            } else {
                return Redirect::route('dashboard')
                                ->withErrors(array("error" => "That Profile is already registered"));
            }
        } else {
            var_dump($adapter);
            die();
        }
        return Redirect::route('dashboard');
    }

    /**
     * 
     * @return type
     */
    public function loginUser() {
        //Get Adapter
        $adapter = $this->socialLogin();
        //var_dump($adapter);die();
        //Get User Profile from Adapter
        //LoginHelper::printFormatVAr($adapter->adapter);
        //echo $adapter->getUserProfile()->identifier;
        $profile = $adapter->getUserProfile();

        //LoginHelper::printFormatVAr($adapter);
        //Look for profile in DB
        $userProfile = SocialProfile::find($profile->identifier);
        //die();
        //If Found
        if ($userProfile) {
            //Get User
            $user = $userProfile->user()->getResults();

            //Log in User
            return $this->login($user);
            //echo "logfed in: " . Auth::check() . '<br>';
            //LoginHelper::printFormatVAr(LoginHelper::getHybridAuthObject()
            //               ->getAdapter('twitter'));
        } else {
            //echo "Profile Not fount";
            //echo "Registering profile..";
            $newUser = $this->registerUser($adapter);
            $this->registerSocialProfile($adapter, $newUser);
            return $this->login($newUser);
        }
    }

}
