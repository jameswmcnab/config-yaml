<?php

namespace Jameswmcnab\ConfigYaml;

interface RepositoryInterface
{
    /**
     * Determine if the given configuration value exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key);

    /**
     * Determine if a configuration group exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasGroup($key);

    /**
     * Get a single item or group of items by key.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return string|array
     */
    public function get($key, $default = null);

    /**
     * Add a new namespace to the loader.
     *
     * @param string $namespace
     * @param string $hint
     */
    public function addNamespace($namespace, $hint);

    /**
     * Returns all registered namespaces with the config
     * loader.
     *
     * @return array
     */
    public function getNamespaces();

    /**
     * Get the loader implementation.
     *
     * @return \Jameswmcnab\ConfigYaml\LoaderInterface
     */
    public function getLoader();

    /**
     * Set the loader implementation.
     *
     * @param \Jameswmcnab\ConfigYaml\LoaderInterface $loader
     */
    public function setLoader(LoaderInterface $loader);
}
