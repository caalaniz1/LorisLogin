<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class HybridSessions extends Eloquent {

    //all elements are mass fillable
    protected $guarded = array();

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_sessions';

    public static function getHybridAuthObject() {
        //Get current Route
        $route_name = Route::currentRouteName();
        //Get configuration File
        $_config = include app_path() . '/config/hybridauth.php';
        $_config['base_url'] = route($route_name) . '?action=auth';
        //GetObject
        $hybridAuth = new Hybrid_Auth($_config);
        //Return Object
        return $hybridAuth;
    }

    /**
     * Attempts login to a social network
     * 
     * @param string $network, Network string identifier, to obtain provider
     * @default string facebook
     * 
     * @return Hybrid_Provider_Adapter Authenticated Hybrid Auth Adapter
     */
    public static function attemptLogin($network = "facebook") {

        if (Input::get('action') == "auth") {
            Hybrid_Endpoint::process();
        }
        try {
            //Get Hybridauth Intance
            $HAuth = HybridSessions:: getHybridAuthObject();

            //try to autenticate with network
            $adapter = $HAuth->authenticate($network);
            return $adapter;
        } catch (Exception $e) {
            return $e;
        }
    }

    public static function create(array $attributes = array("hybridauth_sessions" => NULL)) {
        return parent::create($attributes);
    }

    public function saveConnection($sessionData) {
        $this->hybridauth_sessions = $sessionData;
        return $this->save() ? true : false;
    }

    public function updateSelf() {

        $HAuth = HybridSessions::getHybridAuthObject();

        $stored_session = unserialize($this->hybridauth_sessions);


        $new_session = unserialize($HAuth->getSessionData());

        //if both are arrays
        //Merge
        if (is_array($new_session) && is_array($stored_session)) {
            $restore_session = serialize(
                    array_merge($new_session, $stored_session));
        }
        //if new session is array but no stored array present
        //store new session
        else if (is_array($new_session) && !is_array($stored_session)) {
            $restore_session = serialize($new_session);
        }

        //if stored session is array but there's no new session
        //just restore the old session
        else if (is_array($stored_session) && !is_array($new_session)) {
            $restore_session = serialize($stored_session);
        }

        //if no sessions available the user has no sessions
        //Return;
        else {
            return false;
        }

        //Restore the new session
        $HAuth->restoreSessionData($restore_session);
        //Save it to the database
        $this->saveConnection($HAuth->getSessionData());
        return true;
    }

    public function user() {
        return $this->belongsTo('User', 'user_id');
    }

}
