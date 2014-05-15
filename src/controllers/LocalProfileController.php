<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include("rules.php");
class localProfileController extends BaseController {

    protected $rules = array(
        'firstName' => 'required|alpha',
        'lastName' => 'required|between:5,15|alpha',
        'birthDay' => 'integer|between:0,31',
        'birthMonth' => 'integer|between:0,12',
        'birthYear' => 'required|integer|between:0,3000',
        'description' => 'between:15,3000',
        'gender' => 'alpha|required',
        'address' => 'required|max:30|alpha_spaces',
    );

    /**
     * Takes an array and format it to the corret input format for the Database
     * 
     * @param array $input
     * @return array Mixed
     */
    protected static function formatInput(array $input) {
        return array(
            'first_name' => $input['firstName'],
            'last_name' => $input['lastName'],
            'description' => $input['description'],
            'gender' => $input['gender'],
            'date_of_birth' => $input['birthYear'] . '-' . $input['birthMonth'] . '-' . $input['birthDay'],
            'address' => $input['address'],
            'country' => NULL,
            'city' => NULL,
            'zip' => NULL,
        );
    }

    public function update() {
        try {
           
            $input = Input::all();
            //get integer values for month, day and year
            $input['birthDay'] = intval($input['birthDay']);
            $input['birthMonth'] = intval($input['birthMonth']);
            $input['birthYear'] = intval($input['birthYear']);
            //check for valid input
            $validator = Validator::make( 
                    $input, /* Form Input */ 
                    $this->rules, /* Validation rules */ 
                    $GLOBALS['$validator_messages'] /* Error messages */
            );
            //check for failure and return
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }
            /* At this point all data is valid. */
            //get data in corret format
            $data = $this->formatInput($input);
            Auth::user()->updateLocalProfile($data);
            //Done return
            return Redirect::route('dashboard');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}
