<?php namespace Arcanedev\Moduly\Entities;

use Arcanedev\Moduly\Exceptions\FileMissingException;
use Arcanedev\Moduly\Exceptions\ServiceProviderNotFoundException;
use Arcanedev\Support\Json;

/**
 * Class Module
 * @package Arcanedev\Moduly\Entities
 */
class Module
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var string */
    public $slug = '';

    /** @var string */
    public $name = '';

    /** @var string */
    public $description = '';

    /** @var string */
    public $version = '';

    /** @var string */
    protected $provider = '';

    /** @var bool */
    public $enabled = false;

    /** @var int */
    public $order = 9000;

    /** @var string  */
    protected $path = '';

    /** @var Json */
    protected $json;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Module constructor
     *
     * @param  string  $path
     *
     * @throws FileMissingException
     */
    public function __construct($path)
    {
        $this->setPath($path);

        if ( ! $this->hasJsonFile()) {
            throw new FileMissingException('The module.json file not found in [' . $this->path .']');
        }

        $this->load();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get module path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set module path
     *
     * @param  string  $path
     *
     * @return self
     */
    private function setPath($path)
    {
        $this->checkPath($path);
        $this->path = $path;

        return $this;
    }

    /**
     * Get module service provider
     *
     * @throws ServiceProviderNotFoundException
     *
     * @return string
     */
    public function getProvider()
    {
        $provider = $this->provider;

        if ($this->hasProvider() && class_exists($provider)) {
            return $provider;
        }
        elseif (class_exists($provider = $this->getGeneratedProvider())) {
            return $provider;
        }

        throw new ServiceProviderNotFoundException(
            "Service provider [{$provider}] not found in [{$this->slug}]"
        );
    }

    private function getGeneratedProvider()
    {
        $package   = studly_case($this->name);

        return implode('\\', [
            moduly()->getNamespace() . $package,
            "{$package}ServiceProvider"
        ]);
    }

    /**
     * Check if module is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return (bool) $this->enabled;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Load module
     *
     * @return self
     */
    public function load()
    {
        $this->json        = Json::make($this->path . '/module.json');
        $this->slug        = str_slug($this->json->name);
        $this->name        = $this->json->name;
        $this->description = $this->json->description;
        $this->version     = $this->json->version;
        $this->provider    = $this->json->provider;
        $this->enabled     = $this->json->enabled;
        $this->order       = $this->json->order;

        return $this;
    }

    /**
     * Enable module
     *
     * @return bool
     */
    public function enable()
    {
        return $this->updateEnabled(true);
    }

    /**
     * Disable module
     *
     * @return bool
     */
    public function disable()
    {
        return $this->updateEnabled(false);
    }

    /**
     * Update module enable attribute
     *
     * @param  bool  $enabled
     *
     * @return bool
     */
    private function updateEnabled($enabled)
    {
        if ($this->enabled === $enabled) {
            return true;
        }

        $this->enabled = $enabled;

        return $this->json->update([
            'enabled' => $enabled,
        ]);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Function
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check the path
     *
     * @param  string  $path
     */
    private function checkPath(&$path)
    {
        $path = realpath($path);
        // TODO: Add an exception if path does not exist
    }

    /**
     * Check if package has module.json
     *
     * @return bool
     */
    private function hasJsonFile()
    {
        return realpath($this->path . '/module.json') !== false;
    }

    /**
     * Check if module has a service provider
     *
     * @return bool
     */
    public function hasProvider()
    {
        return ! empty($this->provider);
    }

    /**
     * Check if module has an auto-generated service provider
     *
     * @return bool
     */
    public function hasGeneratedProvider()
    {
        return class_exists($this->getGeneratedProvider());
    }
}
