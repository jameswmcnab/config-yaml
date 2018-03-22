<?php

namespace Jameswmcnab\ConfigYaml;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Yaml\Parser as SymfonyYamlParser;

class ConfigYamlServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        // Merge default configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../config/config-yaml.php', 'config-yaml'
        );

        // Register default Loader
        $this->registerDefaultLoader();

        // Register default Repository
        $this->registerDefaultRepository();

        // Register facade accessor
        $this->app->alias(RepositoryInterface::class, 'config-yaml');
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'../config/config-yaml.php' => config_path('config-yaml.php'),
        ]);
    }

    /**
     * Register default loader implementation.
     *
     * @return void
     */
    protected function registerDefaultLoader()
    {
        $this->app->singleton(LoaderInterface::class, function (Application $app) {
            $defaultPath = $app['config']['config-yaml.yaml_path'];

            return new YamlFileLoader(
                $app['files'],
                new Parser(new SymfonyYamlParser),
                $defaultPath
            );
        });
    }

    /**
     * Register default repository implementation
     *
     * @return void
     */
    protected function registerDefaultRepository()
    {
        $this->app->singleton(RepositoryInterface::class, function (Application $app) {
            return new Repository($app->make(LoaderInterface::class));
        });
    }
}
