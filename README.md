# YAML Config Loader for Laravel

[![Build Status](https://travis-ci.org/jameswmcnab/config-yaml.svg?branch=master)](https://travis-ci.org/jameswmcnab/config-yaml)

This provides simple YAML config loading to Laravel. It takes a lot of inspiration from the `illuminate/config` package 
and uses the Symfony YAML parser.

This is not a replacement for the built-in PHP config file system in Laravel but is intended as an extra 'layer' of 
configuration. This allows you to have one or more Ruby-like `config.yaml` files containing user-configurable config 
for your application.

By default the package assumes your YAML files are in the `base_path()` directory, but you can customise 
this by publishing the package config file (`config-yaml.php`) to your application and changing the `yaml_path` key.

## Installation

Installation is via Composer:

```bash
$ composer require jameswmcnab/config-yaml
```

#### Publish package config (optional)

If you want to customise the package config, publish the package config then edit the newly created `config/config-yaml.php` file:

```bash
$ php artisan vendor:publish --provider=ConfigYamlServiceProvider
```

## Usage

Example YAML file:

``` yaml
# Example YAML config.yaml file
app:
  name: "Great App"
  version: 1.0.2

log:
  dir: /var/log/vendor/app
  level: debug

database:
  adapter: mysql
  database: app_live
  username: user
  password: password
````

#### Using the facade

**Note: Remember to register the facade in your `app.php` config.**

``` php
ConfigYaml::get('config.database.adapter'); // mysql
```

#### Using dependency injection

If you don't want to use the facade just directly inject `Jameswmcnab\ConfigYaml\RepositoryInterface` wherever dependency 
injection is supported and use it directly:

``` php
<?php

namespace App\Foo;

use Jameswmcnab\ConfigYaml\RepositoryInterface;

class FooBar
{
    /**
     * @var RepositoryInterface
     */
    private $yamlConfig;

    /**
     * FooBar constructor.
     *
     * @param RepositoryInterface $yamlConfig
     */
    public function __construct(RepositoryInterface $yamlConfig)
    {
        $this->yamlConfig = $yamlConfig;
    }

    /**
     * @return array|string
     */
    private function getDatabaseAdapter()
    {
        return $this->yamlConfig->get('config.database.adapter');  // mysql
    }
}
```

## Running Tests

To run the package tests:

``` bash
$ vendor/bin/phpspec run
$ vendor/bin/phpunit
```
