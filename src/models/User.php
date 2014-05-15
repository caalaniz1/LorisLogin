<?php
/**
 *
 * 
 * User Model
 * Privilages:
 * -1 - Unactive Email required
 * 0 - Unactive
 * 1 - Not Confirmed
 * 2 - Confirmed
 * 
 * 3-5 Undefined 
 * 6 - Admministrator
 *  
 */

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

    public function getRememberToken() {
        return $this->remember_token;
    }

    public function setRememberToken($value) {
        $this->remember_token = $value;
    }

    public function getRememberTokenName() {
        return 'remember_token';
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail() {
        return $this->email;
    }

    /**
     * Retusn the setings array
     * 
     * @return array
     */
    public function getFileSettings() {
        $folderPath = $this->picture_Path . $this->id;
        return json_decode(
                file_get_contents($folderPath . "/setting.json"), true);
    }

    /**
     * return the app path to the user's profile pictures
     *  Ex. "uploads/profilePictures/14"
     * @return string
     */
    public function getFolderPath() {
        return $this->picture_Path . $this->id;
    }

    /**
     * 
     * Returns profile picture[$key] URL 
     * 
     * @param string $key
     * @return string
     */
    public function getPictureURL($key) {
        $settings = $this->getFileSettings();
        return (isset($settings["paths"][$key])) ?
                URL::asset($this->getFolderPath() . '/' . $key) :
                NULL;
    }

    //Relationships

    public function socialProfiles() {
        return $this->hasMany('SocialProfile', 'user_id');
    }

    public function localProfile() {
        return $this->hasOne('LocalProfile', 'user_id');
    }

    public function hybridSessions() {
        return $this->hasOne('HybridSessions', 'user_id');
    }

    //Actions
    /**
     * Create a a folder to contain the profile pictures of each user.
     * then executes create() method
     * 
     * @param array $attributes
     * @return User
     */
    public static function create(array $attributes) {
        $newUser = parent::create($attributes);
        $folderPath = $newUser->picture_Path . $newUser->id;
        if (!file_exists($folderPath)) {
            //create directory for profilepictures
            mkdir($folderPath, 0777, true);
            //add setings file
            file_put_contents($folderPath . "/setting.json", json_encode($newUser->picture_info));
        }
        return $newUser;
    }
    
    public static function 
            createWithSocial(array $userAttributes, array $profileAttributes){
        $newUser = User::create($userAttributes);
        $newUser->socialProfiles()->create($profileAttributes);
        $newUser->updateSession();
        return $newUser;
        
    }

    public function updateSession() {
        $session = $this->hybridSessions()->getResults();

        if (!$session) {
            $session = $this->hybridSessions()->create(
                    array('user_id' => $this->id)
            );
        }

        return $session->updateSelf();
    }

    /**
     * 
     * updates user's localprofile
     * 
     * @param array $data
     * @return localProfile
     */
    public function updateLocalProfile(array $data) {
        try {
            if ($this->localProfile()) {
                $this->localProfile()->update($data);
            } else {
                $this->localProfile()->create($data);
            }
            return $this->localProfile();
        } catch (Exception $e) {
            return $e;
        }
    }

}
