<?php namespace RedPanda\LorisLogin\Facades;

use Illuminate\Support\Facades\Facade;

class LorisUser extends Facade {

	/**
	* Get the registered name of the component.
	*
	* @return string	
	*/
	protected static function getFacadeAccessor(){
		return "Lorisuser";
	}

}
