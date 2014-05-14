<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

    protected $guarded = array();
    //The folder used to store profile pictures
    protected $picture_Path = "uploads/profilePictures/";
    protected $maxUploads = 5;
    protected $picture_info = array(
        "size" => 0,
        "paths" => array()
    );

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password');

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier() {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword() {
        return $this->password;
    }

    public static function create(array $attributes) {
        $newUser = parent::create($attributes);
        $folderPath = $newUser->picture_Path . $newUser->id;
        if (!file_exists($folderPath)) {
            //create directory for profilepictures
            mkdir($folderPath, 0777, true);
            //add setings file
            file_put_contents($folderPath . "/setting.json", 
                    json_encode($newUser->picture_info));
        }
        return $newUser;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail() {
        return $this->email;
    }

    public function socialProfiles() {
        return $this->hasMany('SocialProfile');
    }

    public function localProfile() {
        return $this->hasOne('LocalProfile', 'user_id');
    }

    public function hybridSessions() {
        return $this->hasOne('HybridSessions', 'user_id');
    }
    /**
     * Retusn the setings array
     * 
     * @return array
     */
    public function getFileSettings(){
        $folderPath = $this->picture_Path . $this->id;
        return json_decode(
                file_get_contents($folderPath . "/setting.json"), true);
    }
    /**
     * return the app path to the user's profile pictures
     *  Ex. "uploads/profilePictures/14"
     * @return string
     */
    public function getFolderPath(){
        return $this->picture_Path . $this->id;
    }
    
    public function getPictureURL($key){
        $settings = $this->getFileSettings();
        return (isset($settings["paths"][$key]))?
        URL::asset($this->getFolderPath().'/'.$key):
        NULL;
    }

    public function getRememberToken() {
        return $this->remember_token;
    }

    public function setRememberToken($value) {
        $this->remember_token = $value;
    }

    public function getRememberTokenName() {
        return 'remember_token';
    }

}
