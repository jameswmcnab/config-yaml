<?php

namespace Jameswmcnab\ConfigYaml\Tests\Integration;

use Illuminate\Foundation\Application;
use Jameswmcnab\ConfigYaml\ConfigYamlServiceProvider;
use Jameswmcnab\ConfigYaml\Facades\ConfigYaml;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        config()->set('config-yaml.yaml_path', __DIR__ . '/../fixtures');
    }

    /**
     * @param Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            ConfigYamlServiceProvider::class,
        ];
    }

    /**
     * @param Application $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'ConfigYaml' => ConfigYaml::class,
        ];
    }
}
