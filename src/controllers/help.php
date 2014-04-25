<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class LoginHelper{

    //debugging
    protected static function printFormatVar($var) {
        echo '<pre>';
        echo print_r($var);
        echo '</pre>';
    }

    //debugging
    protected static $errorCodes = array(
        '01' => "\$_GET['network'] not defined",
        '02' => "Profile not Found",
    );
    
    protected static $successCodes = array();

    /**
     * Replace white spaces with underscore
     * 
     * @param string $name
     * @return tring
     */
    protected static function formatName($name = NULL) {
        return !$name ? NULL : preg_replace('/\s+/', '_', $name);
    }

    /**
     * 
     * @return Hybrid_Auth
     */
    public static function getHybridAuthObject() {
        //Get current Route
        $route_name = Route::currentRouteName();
        //Get configuration File
        $_config = include app_path() . '/config/hybridauth.php';
        $_config['base_url'] = route($route_name) . '?action=auth';
        //GetObject
        $hybridAuth = new Hybrid_Auth($_config);
        //Return Object
        return $hybridAuth;
    }

}