<?php

class User extends BaseController {

    
    protected $rules = array(
        'username' => 'required|alpha_dash|unique:users,username',
        'password' => 'required|same:password-r|alpha_dash',
        'password-r' => 'required|same:password|alpha_dash',
        'email' => 'required|email|unique:users,email',
    );

    /**
     * Takes an array and format it to the corret input format for the Database
     * 
     * @param array $input
     * @return array Mixed
     */
    protected static function formatInput($input) {
        return array(
            'username' => $input['username'],
            'password' => Hash::make($input['password']),
            'email' => $input['email'],
            'privileges' => 1,
        );
    }

    public function create() {
        try {
            //variables
            $input = Input::all();
            //validator
            $validator = Validator::make(
                    $input, /* Form Input */ 
                    $this->rules, /* Validation rules */ 
                    $GLOBALS['$validator_messages'] /* Error messages */
            );
            //check for failure
            if ($validator->fails()) {
                return Redirect::back()
                                ->withErrors($validator)
                                ->withInput(Input::except('password'));
            }
            //At this point the data is valid
            //Format Data for DB
            $data = $this->formatInput($input);
            
            //create new user
            $newUser = User::create($data);
            //login new user and redirect to dashboard
            Auth::login($newUser);
            return Redirect::route('dashboard');
            
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}

