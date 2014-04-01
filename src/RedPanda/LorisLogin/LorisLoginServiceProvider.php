<?php namespace RedPanda\LorisLogin;

use Illuminate\Support\ServiceProvider;

class LorisLoginServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->package('RedPanda/LorisLogin');
 		include __DIR__ . '/../../routes.php';
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		
		$this->app['LorisUser'] = $this->app->share(function($app){
			return new LorisUser;
		});
		
		$this->app->booting(function(){
			$loader = \Illuminate\Foundation\AliasLoader::getInstance();
			$loader -> alias('LorisUser', 'RedPanda\LorisLogin\Facades\LorisUser');
		});

	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
