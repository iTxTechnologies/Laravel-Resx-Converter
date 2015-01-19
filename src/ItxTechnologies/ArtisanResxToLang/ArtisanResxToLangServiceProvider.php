<?php namespace ItxTechnologies\ArtisanResxToLang;

use Illuminate\Support\ServiceProvider;

class ArtisanResxToLangServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app['resxToLang'] = $this->app->share(function($app)
		{
			return new ArtisanResxToLang;
		});

		$this->commands('resxToLang');
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
