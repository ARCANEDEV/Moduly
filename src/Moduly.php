<?php namespace Arcanedev\Moduly;

use Arcanedev\Moduly\Contracts\ModulyInterface;
use Arcanedev\Moduly\Entities\Module;
use Arcanedev\Moduly\Entities\ModulesCollection;
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
     * Get namespace
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->config->get('moduly.modules.namespace');
    }

    /**
     * Set modules base path
     *
     * @param  string  $basePath
     *
     * @return self
     */
    private function setBasePath($basePath)
    {
        $this->basePath = $basePath;

        return $this;
    }

    /**
     * Get module path
     *
     * @param  string  $module
     *
     * @return string
     */
    public function getModulePath($module)
    {
        $module = str_slug($module);

        return $this->basePath . "/{$module}/";
    }

    /**
     * Get all modules
     *
     * @return \Arcanedev\Moduly\Entities\ModulesCollection
     */
    public function modules()
    {
        // Reload all modules
        $this->modules->load($this->basePath);

        return $this->modules;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
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
     * @return \Arcanedev\Moduly\Entities\ModulesCollection
     */
    public function all()
    {
        return $this->modules();
    }

    /**
     * Get all module slugs
     *
     * @return array
     */
    public function slugs()
    {
        return $this->modules()->slugs();
    }

    /**
     * Get a module
     *
     * @param  string  $module
     *
     * @return \Arcanedev\Moduly\Entities\Module
     */
    public function get($module)
    {
        return $this->where('slug', $module)->first();
    }

    /**
     * Get modules based on where clause.
     *
     * @param  string  $key
     * @param  mixed   $value
     *
     * @return \Arcanedev\Support\Collection
     */
    public function where($key, $value)
    {
        return $this->modules()->where($key, $value);
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

        return $this->modules()->has($name);
    }

    /**
     * Returns count of all modules.
     *
     * @return int
     */
    public function count()
    {
        return $this->modules()->count();
    }

    /**
     * Gets all enabled modules.
     *
     * @return \Arcanedev\Moduly\Entities\ModulesCollection
     */
    public function enabled()
    {
        return $this->modules()->enabled();
    }

    /**
     * Gets all disabled modules.
     *
     * @return \Arcanedev\Moduly\Entities\ModulesCollection
     */
    public function disabled()
    {
        return $this->modules()->disabled();
    }

    /**
     * Enables the specified module.
     *
     * @param  string  $name
     *
     * @return bool
     */
    public function enable($name)
    {
        return $this->modules()->enable($name);
    }

    /**
     * Disables the specified module.
     *
     * @param  string  $name
     *
     * @return bool
     */
    public function disable($name)
    {
        return $this->modules()->disable($name);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if module is enabled
     *
     * @param  string  $name
     *
     * @return bool
     */
    public function isEnabled($name)
    {
        return $this->modules()->isEnabled($name);
    }

    /**
     * Check if specified module is disabled.
     *
     * @param  string  $name
     *
     * @return bool
     */
    public function isDisabled($name)
    {
        return $this->modules()->isDisabled($name);
    }
}
