<?php

class SocialProfile  extends Eloquent{

    /**
     * Set of Validation Rules   
     */
    protected $guarded = array();
    protected $primaryKey = 'identifier';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'social_profiles';

    public function user() {
        return $this->belongsTo('User', 'user_id');
    }

}
