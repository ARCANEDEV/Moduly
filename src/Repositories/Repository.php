<?php namespace Arcanedev\Moduly\Repositories;

use Arcanedev\Moduly\Contracts\ModuleRepositoryInterface;
use Illuminate\Config\Repository as Config;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

/**
 * Class Repository
 * @package Arcanedev\Moduly\Repositories
 */
abstract class Repository implements ModuleRepositoryInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * @var string $path Path to the defined modules directory
     */
    protected $path;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Constructor method.
     *
     * @param Config     $config
     * @param Filesystem $files
     */
    public function __construct(Config $config, Filesystem $files)
    {
        $this->config = $config;
        $this->files  = $files;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get all module basenames
     *
     * @return Collection
     */
    protected function getAllBasenames()
    {
        $path = $this->getPath();

        try {
            $collection = collect($this->files->directories($path));

            return $collection->map(function($item) {
                return basename($item);
            });
        }
        catch (\InvalidArgumentException $e) {
            return collect();
        }
    }

    /**
     * Get modules path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path ?: $this->config->get('moduly.path');
    }

    /**
     * Set modules path in "RunTime" mode.
     *
     * @param  string $path
     *
     * @return self
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path for the specified module.
     *
     * @param  string $module
     *
     * @return string
     */
    public function getModulePath($module)
    {
        return $this->getPath() . "/{$module}/";
    }

    /**
     * Get path of module JSON file.
     *
     * @param  string $module
     * @return string
     */
    protected function getManifestPath($module)
    {
        return $this->getModulePath($module) . 'module.json';
    }

    /**
     * Get modules namespace.
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->config->get('moduly.namespace');
    }
}
