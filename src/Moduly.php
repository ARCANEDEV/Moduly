<?php namespace Arcanedev\Moduly;

use Arcanedev\Moduly\Contracts\ModulyInterface;
use Arcanedev\Moduly\Entities\Module;
use Arcanedev\Moduly\Entities\ModulesCollection;
use Arcanedev\Support\Collection;
use Illuminate\Config\Repository as Config;
use Illuminate\Foundation\Application;

/**
 * Class Moduly
 * @package Arcanedev\Moduly
 */
class Moduly implements ModulyInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const KEY_NAME = 'arcanedev.moduly';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Application */
    private $app;

    /** @var Config */
    private $config;

    /**
     * Base path
     *
     * @var string
     */
    protected $basePath = '';

    /**
     * @var ModulesCollection
     */
    protected $modules;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    public function __construct(Application $app, Config $config)
    {
        $this->app     = $app;
        $this->config  = $this->app['config'];
        $this->modules = new ModulesCollection;

        $this->setBasePath($this->config->get('moduly.modules.path'));
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get modules base path
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->config->get('moduly.modules.namespace');
    }

    /**
     * Set modules base path
     *
     * @param  string $basePath
     *
     * @return self
     */
    private function setBasePath($basePath)
    {
        $this->basePath = $basePath;

        return $this;
    }

    public function getModulePath($module)
    {
        $module = str_slug($module);

        return $this->basePath . "/{$module}/";
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    private function reloadModules()
    {
        $this->modules->load($this->basePath);
    }

    /**
     * Register all modules
     */
    public function register()
    {
        $this->all()->each(function(Module $module) {
            if ($module->hasProvider()) {
                $this->app->register($module->getProvider());
            }
        });
    }

    /**
     * Get all modules
     *
     * @return ModulesCollection
     */
    public function all()
    {
        $this->reloadModules();

        return $this->modules;
    }

    /**
     * Get all module slugs
     *
     * @return array
     */
    public function slugs()
    {
        return $this->all()->slugs();
    }

    /**
     * Get a module
     *
     * @param  string $module
     *
     * @return Module
     */
    public function get($module)
    {
        return $this->where('name', $module)->first();
    }

    /**
     * Get modules based on where clause.
     *
     * @param  string  $key
     * @param  mixed   $value
     *
     * @return Collection
     */
    public function where($key, $value)
    {
        $key = str_slug($key);

        $this->reloadModules();

        return $this->modules->where($key, $value);
    }

    /**
     * Check if the given module exists.
     *
     * @param  string  $name
     *
     * @return bool
     */
    public function exists($name)
    {
        $name = str_slug($name);

        return $this->all()->has($name);
    }

    /**
     * Returns count of all modules.
     *
     * @return int
     */
    public function count()
    {
        return $this->all()->count();
    }

    /**
     * Gets all enabled modules.
     *
     * @return ModulesCollection
     */
    public function enabled()
    {
        $this->reloadModules();

        return $this->modules->enabled();
    }

    /**
     * Gets all disabled modules.
     *
     * @return ModulesCollection
     */
    public function disabled()
    {
        $this->reloadModules();

        return $this->modules->disabled();
    }

    /**
     * Enables the specified module.
     *
     * @param  string $name
     *
     * @return bool
     */
    public function enable($name)
    {
        $this->reloadModules();

        return $this->modules->enable($name);
    }

    /**
     * Disables the specified module.
     *
     * @param  string $name
     *
     * @return bool
     */
    public function disable($name)
    {
        $this->reloadModules();

        return $this->modules->disable($name);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if module is enabled
     *
     * @param  string $name
     *
     * @return bool
     */
    public function isEnabled($name)
    {
        $this->reloadModules();

        return $this->modules->isEnabled($name);
    }

    /**
     * Check if specified module is disabled.
     *
     * @param  string $name
     *
     * @return bool
     */
    public function isDisabled($name)
    {
        $this->reloadModules();

        return $this->modules->isDisabled($name);
    }
}
