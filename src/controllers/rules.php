<?php

Validator::extend('alpha_spaces', function($attribute, $value)
{
    return preg_match("/^[\w\-.\s]+$/", $value);
});
Validator::extend('dimension', function($attribute, $value)
{
    return preg_match("/^[\w\-.\s]+$/", $value);
});

$GLOBALS['$validator_messages'] = array(
    
    "alpha_spaces" => "The :attribute field only allows letters, numbers, dots and underscores"
    
);