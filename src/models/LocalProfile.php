<?php

use LaravelBook\Ardent\Ardent;

class LocalProfile extends Ardent {
    //all elements are mass fillable
    protected $guarded = array();

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'local_profiles';

    public function user() {
        return $this->belongsTo('User', 'user_id');
    }

}
