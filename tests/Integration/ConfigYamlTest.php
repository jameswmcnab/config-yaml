<?php

namespace Jameswmcnab\ConfigYaml\Tests\Integration;

use Jameswmcnab\ConfigYaml\CachingRepository;
use Jameswmcnab\ConfigYaml\Facades\ConfigYaml;
use Jameswmcnab\ConfigYaml\Repository;
use Jameswmcnab\ConfigYaml\RepositoryInterface;
use Illuminate\Contracts\Cache\Repository as Cache;

class ConfigYamlTest extends TestCase
{

    public function testDefaultRepository()
    {
        $expected = [
            'foo' => ['bar' => 'foobar'],
            'baz' => ['bat' => 'bazbat'],
        ];

        $this->assertEquals($expected, ConfigYaml::get('test'));
        $this->assertEquals($expected['foo']['bar'], ConfigYaml::get('test.foo.bar'));
    }

    public function testCachingRepository()
    {
        $this->app->singleton(RepositoryInterface::class, function () {
            return new CachingRepository(
                $this->app->make(Repository::class),
                $this->app->make(Cache::class)
            );
        });

        $this->assertInstanceOf(CachingRepository::class, $this->app->make('config-yaml'));
        $this->assertEquals('bazbat', ConfigYaml::get('test.baz.bat'));
    }
}
