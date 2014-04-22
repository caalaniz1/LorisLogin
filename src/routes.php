<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the Closure to execute when that URI is requested.
  |
 */


Route::get('/', array('as' => 'start', function() {
    return View::make('LorisLogin::blueprint');
}));



Route::get('test', array('as' => 'test', function() {
    $layout = View::make('LorisLogin::blueprint');
    return $layout->nest('content','LorisLogin::forms/login');
}));
Route::post('test', array('as' => 'test', 'before' => 'csrf', function() {
    var_dump(Input::all());
}));
