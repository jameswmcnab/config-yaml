<?php namespace Jameswmcnab\ConfigYaml;

use Illuminate\Filesystem\Filesystem;

class YamlFileLoader implements LoaderInterface {

    /**
     * The Filesystem instance.
     *
     * @type \Illuminate\Filesystem\Filesystem;
     */
    protected $files;

    /**
     * The default path to the application YAML files.
     *
     * @type string
     */
    protected $defaultPath;

    /**
     * The YAML Parser instance.
     *
     * @type \Jameswmcnab\ConfigYaml\Parser
     */
    protected $parser;

    /**
     * All of the named path hints.
     *
     * @var array
     */
	protected $hints = array();

	/**
     * A cache of whether namespaces and groups exists.
     *
     * @var array
     */
	protected $exists = array();

    /**
     * @param  \Illuminate\Filesystem\Filesystem   $files
     * @param  \Jameswmcnab\ConfigYaml\Parser      $parser
     * @param  string      $defaultPath
     */
    public function __construct(Filesystem $files, Parser $parser, $defaultPath)
    {
        $this->files = $files;
        $this->parser = $parser;
        $this->defaultPath = $defaultPath;
    }

    /**
     * Load the configuration group for the key.
     *
     * @param  string  $group
     * @param  string  $namespace
     * @return string
     */
    public function load($group, $namespace = null)
    {
        $string = null;

        // First we'll get the root configuration path for the environment which is
        // where all of the configuration files live for that namespace, as well
        // as any environment folders with their specific configuration items.
        $path = $this->getPath($namespace);

        if (is_null($path))
        {
            return $string;
        }

        // First we'll get the main configuration file for the groups. Once we have
        // that we can check for any environment specific files, which will get
        // merged on top of the main arrays to make the environments cascade.
        $file = "{$path}/{$group}.yaml";

        if ($this->files->exists($file))
        {
            $string = $this->files->get($file);
        }

        // Parse string as YAML
        return $this->parser->parseString($string);
    }

    /**
     * Determine if the given configuration group exists.
     *
     * @param  string  $group
     * @param  string  $namespace
     * @return bool
     */
    public function exists($group, $namespace = null)
    {
        $key = $group.$namespace;

        // We'll first check to see if we have determined if this namespace and
        // group combination have been checked before. If they have, we will
        // just return the cached result so we don't have to hit the disk.
        if (isset($this->exists[$key]))
        {
            return $this->exists[$key];
        }

        $path = $this->getPath($namespace);

        // To check if a group exists, we will simply get the path based on the
        // namespace, and then check to see if this files exists within that
        // namespace. False is returned if no path exists for a namespace.
        if (is_null($path))
        {
            return $this->exists[$key] = false;
        }

        $file = "{$path}/{$group}.yaml";

        // Finally, we can simply check if this file exists. We will also cache
        // the value in an array so we don't have to go through this process
        // again on subsequent checks for the existing of the config file.
        $exists = $this->files->exists($file);

        return $this->exists[$key] = $exists;
    }

    /**
     * Add a new namespace to the loader.
     *
     * @param  string  $namespace
     * @param  string  $hint
     * @return void
     */
    public function addNamespace($namespace, $hint)
    {
        $this->hints[$namespace] = $hint;
    }

    /**
     * Returns all registered namespaces with the config
     * loader.
     *
     * @return array
     */
    public function getNamespaces()
    {
        return $this->hints;
    }

    /**
     * Get the configuration path for a namespace.
     *
     * @param  string  $namespace
     * @return string
     */
    protected function getPath($namespace)
    {
        if (is_null($namespace))
        {
            return $this->defaultPath;
        }
        elseif (isset($this->hints[$namespace]))
        {
            return $this->hints[$namespace];
        }
    }

}