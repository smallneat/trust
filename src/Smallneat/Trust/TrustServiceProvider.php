<?php namespace Smallneat\Trust;

use Illuminate\Support\ServiceProvider;

class TrustServiceProvider extends ServiceProvider {


	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;




    /**
     * Called to boot the package
     */
    public function boot()
    {
        $this->package('smallneat/trust');
    }



    /**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->app['command.trust.migration'] = $this->app->share(function($app)
        {
            return new MigrationCommand($app);
        });

        $this->commands('command.trust.migration');
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
