<?php


use LaravelBook\Ardent\Ardent;

class LocalProfile extends Ardent {

    /**
     * Set of Validation Rules   
     */
    public static $rules = array(
        'first_name' => 'alpha|required|between:2,20',
        'last_name' => 'alpha|required|between:2,20',
        'email' => 'required|email',
    );

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
