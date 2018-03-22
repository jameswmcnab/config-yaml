<?php

namespace Jameswmcnab\ConfigYaml;

use Illuminate\Contracts\Cache\Repository as Cache;

class CachingRepository implements RepositoryInterface
{
    /**
     * The time to cache items in minutes.
     *
     * @var int
     */
    protected $cacheAgeMinutes = 60;

    /**
     * The repository instance.
     *
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * The cache repository instance.
     *
     * @var Cache
     */
    protected $cache;

    /**
     * @param Repository $repository
     * @param Cache $cache
     */
    public function __construct(Repository $repository, Cache $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * Determine if the given configuration value exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return $this->cache->has($key) || $this->repository->has($key);
    }

    /**
     * Determine if a configuration group exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasGroup($key)
    {
        return $this->cache->has($key) || $this->repository->hasGroup($key);
    }

    /**
     * Get a single item or group of items by key.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return string|array
     */
    public function get($key, $default = null)
    {
        return $this->cache->remember($key, $this->cacheAgeMinutes, function (Repository $repository) use ($key, $default) {
           return $repository->get($key, $default);
        });
    }

    /**
     * Add a new namespace to the loader.
     *
     * @param string $namespace
     * @param string $hint
     */
    public function addNamespace($namespace, $hint)
    {
        $this->repository->addNamespace($namespace, $hint);
    }

    /**
     * Returns all registered namespaces with the config
     * loader.
     *
     * @return array
     */
    public function getNamespaces()
    {
        return $this->repository->getNamespaces();
    }

    /**
     * Get the loader implementation.
     *
     * @return LoaderInterface
     */
    public function getLoader()
    {
        return $this->repository->getLoader();
    }

    /**
     * Set the loader implementation.
     *
     * @param LoaderInterface $loader
     */
    public function setLoader(LoaderInterface $loader)
    {
        $this->repository->setLoader($loader);
    }
}
