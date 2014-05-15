<?php

include('rules.php');
class LoginController extends BaseController {

    protected $loginRules = array(
        'username' => 'required|alpha-dash',
        'password' => 'required|between:5,15|alpha-dash'
    );
    protected $registerRules = array(
        'username' => 'required|alpha_dash|unique:users,username',
        'password' => 'required|same:password-r|alpha_dash',
        'password-r' => 'required|same:password|alpha_dash',
        'email' => 'required|email|unique:users,email'
    );

    public function loginWithLocalCredentials() {
        //Get input
        $input = Input::all();
        //validate
        $validator = Validator::make($input, $this->loginRules);
        //check if valid
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)
                            ->withInput(Input::except('password'));
        }
        //at this point everything is valid.
        $credentials = array(
            'username' => $input['username'], 'password' => $input['password']
        );
        //attempt to login
        $user = Auth::Attempt($credentials);
        //if user not available redirect back
        if (!$user) {
            return Redirect::back()
                            ->withErrors(array('login' => 'Please try to login again'))
                            ->withInput(Input::except('password'));
        }
        return Redirect::route("dashboard");
    }

    public function loginWithSocialNetwork() {
        //Get Adapter
        $adapter = HybridSessions::attemptLogin(Input::get("network"));
        //Get User Profile from Adapter
        $profile = $adapter->getUserProfile();
        //Look for profile in DB
        $userProfile = SocialProfile::find($profile->identifier);
        //If Found
        if ($userProfile) {
            //Get User
            $user = $userProfile->user()->getResults();
            //Log in User
            Auth::login($user);
            //Restore/ Create session
            $user->updateSession();
            //Done
            return Redirect::route("dashboard");
        }
        //Create a new User
        return Redirect::route("register-user")->withErrors(
                array("error" => "That account is not associated with any"
                    . "user please create an account"));
    }

    public function logout() {
        Auth::logout();
        $hybridauth = HybridSessions::getHybridAuthObject();
        $hybridauth->logoutAllProviders();
    }

    public function registerWithLocalCredentials() {
        $input = Input::all();
        //Check if valid
        $validator = Validator::make(
                        $input, $this->registerRules, $GLOBALS['$validator_messages'] //rules.php
        );
        //Check for failure
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->
                            withInput(Input::except('password'));
        }
        //At this point we assume valid data
        //format data for user creation
        $data = array(
            'username' => $input['username'],
            'password' => Hash::make($input['password']),
            'confirmation' => hash('sha512', uniqid()),
            'email' => $input["email"]
        );
        //Create user
        $newUser = User::create($data);
        //Send confirmation email
        //$this->sendConfirmation($data["email"], $data["key"]};
        //login user
        Auth::login($newUser);
        return Redirect::route('dashboard');
    }

    public function registerWithSocialNetwork() {

        //Attempt login to Social Network
        try {
            $adapter = HybridSessions::attemptLogin(Input::get("network"));
            if (get_class($adapter) == "Exception") {
                throw $adapter;
            }
            //Get social info
            $UserProfile = $adapter->getUserProfile();
            $username = $UserProfile->identifier . uniqid();
            $password = NULL;
            $email = $adapter->getUserProfile()->emailVerified;
            //if email is null set priv to -1
            $privileges = ($email) ? 1 : -1;
            //format data for user creation
            $userData = array(
                'username' => $username,
                'password' => $password,
                'email' => $email,
                'privileges' => $privileges
            );
            //data for user's social profile
            $socialData = array(
                'provider' => $adapter->id,
                'identifier' => $adapter->getUserProfile()->identifier,
            );
            //create user
            $newUser = User::createWithSocial($userData, $socialData);
            //login user
            Auth::login($newUser);
            return Redirect::route('dashboard');
        } catch (Exception $e) {
            if ($e->getCode() == 23000) {
                return Redirect::route("login-user")
                                ->withErrors(array("error" => "That Profile seems"
                                    . " to be registered already, try login in"));
            }
            return Redirect::route("register-user")
                            ->withErrors(array("error" => $e->getMessage()));
        }
    }

    public function addProvider() {
        try {
            //Get Adapter
            $adapter = HybridSessions::attemptLogin(Input::get("network"));
            $data = array(
                'provider' => $adapter->id,
                'identifier' => $adapter->getUserProfile()->identifier
            );
            Auth::user()->socialProfiles()->create($data);
            //Save session in DB
            Auth::user()->updateSession();
            return Redirect::route('dashboard');
        } catch (Exception $e) {
            if ($e->getCode() == 23000) {
                return Redirect::route('dashboard')
                                ->withErrors(array("error" => "That Profile is "
                                    . "already registered"));
            }
            return Redirect::route('dashboard')
                            ->withErrors(array("error" => $e->getMessage()));
        }
    }

}
