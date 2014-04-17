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

    
    public function saveConnection($sessionData) {
        $this->hybridauth_sessions = $sessionData;
        return $this->save() ? true : false;
    }

    public function user() {
        return $this->belongsTo('User', 'user_id');
    }

}
