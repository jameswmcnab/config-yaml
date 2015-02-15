<?php namespace Jameswmcnab\ConfigYaml\Facades; 

use Illuminate\Support\Facades\Facade;

/**
 * @see \Jameswmcnab\ConfigYaml\Repository
 */
class ConfigYaml extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'config-yaml'; }

}