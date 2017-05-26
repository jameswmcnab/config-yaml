<?php namespace Jameswmcnab\ConfigYaml;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class ConfigYamlServiceProvider extends ServiceProvider {

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
        $configPath = __DIR__.'/../../config/config-yaml.php';
        $this->publishes([$configPath => config_path('config-yaml.php')], 'config');
    }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        // Register default Loader
        $this->registerDefaultLoader();

        // Register default Repository
        $this->registerDefaultRepository();

        // Register facade accessor
        $this->app->singleton('config-yaml', function(Application $app)
        {
            return $app->make('Jameswmcnab\ConfigYaml\RepositoryInterface');
        });

        // Add facade alias
        $this->app->booting(function()
        {
            $loader = AliasLoader::getInstance();
            $loader->alias('ConfigYaml', 'Jameswmcnab\ConfigYaml\Facades\ConfigYaml');
        });
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('config-yaml');
	}

    /**
     * Register default Loader
     *
     * @return void
     */
    protected function registerDefaultLoader()
    {
        $configPath = __DIR__.'/../../config/config-yaml.php';
        $this->mergeConfigFrom($configPath, 'config-yaml');

        $this->app->singleton('Jameswmcnab\ConfigYaml\LoaderInterface', function(Application $app)
        {
            $defaultPath = $app['config']['config-yaml.yaml_path'];

            return new YamlFileLoader(
                $app['files'],
                new Parser(new \Symfony\Component\Yaml\Parser),
                $defaultPath
            );
        });
    }

    /**
     * Register default Repository
     *
     * @return void
     */
    protected function registerDefaultRepository()
    {
        $this->app->singleton('Jameswmcnab\ConfigYaml\RepositoryInterface', function(Application $app)
        {
            return new Repository($app->make('Jameswmcnab\ConfigYaml\LoaderInterface'));
        });
    }

}
