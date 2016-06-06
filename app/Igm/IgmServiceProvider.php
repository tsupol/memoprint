<?php namespace App\Igm;

use Illuminate\Support\ServiceProvider;

class IgmServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
        // Register 'underlyingclass' instance container to our UnderlyingClass object
        $this->app['igm'] = $this->app->share(function($app)
        {
            return new IgmManager;
        });

        // Shortcut so developers don't need to add an Alias in app/config/app.php
        $this->app->booting(function()
        {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('Igm', 'App\Igm\Facades\Igm');
        });
	}

}
