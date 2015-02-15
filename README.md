# YAML Config Loader for Laravel
This provides simple YAML config loading to Laravel. It takes a lot of inspiration from the Illuminate/Config package.
Uses the Symfony YAML component.

This is not a replacement for the built-in PHP config file system in Laravel but is intended as an extra 'layer' of 
configuration. This allows you to have one or more Ruby-like `config.yaml` files containing user-configurable config 
for your application.

By default the package assumes your YAML files are in the `base_path()` directory.

## Installation
Require this package in your `composer.json` file:

~~~json
"jameswmcnab/config-yaml": "dev-master"
~~~

And add the ServiceProvider to the `providers` array in `app/config/app.php` file:

~~~php
'Jameswmcnab\ConfigYaml\ConfigYamlServiceProvider',
~~~

Publish the package config using Artisan (If you want to change the default YAML config file directory).

~~~bash
php artisan config:publish jameswmcnab/config-yaml

## Usage

### Get configuration by key:

~~~yaml
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
~~~

~~~php
ConfigYaml::get('config.database.adapter'); // mysql
~~~