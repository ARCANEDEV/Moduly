<?php namespace Arcanedev\Moduly\Entities;

use Arcanedev\Support\Collection;
use Arcanedev\Moduly\Exceptions\ModuleNotFound;

/**
 * Class     ModulesCollection
 *
 * @package  Arcanedev\Moduly\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ModulesCollection extends Collection
{
    /* ------------------------------------------------------------------------------------------------
     |  Getter & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get ignored modules
     *
     * @return array
     */
    private function getIgnored()
    {
        return config('moduly.modules.ignored', []);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Load modules
     *
     * @param  string  $path
     *
     * @return self
     */
    public function load($path)
    {
        $this->reset();

        array_map(function($dir) {
            $this->loadModule($dir);
        }, glob($path . '/*', GLOB_ONLYDIR));

        $this->items = $this->sortBy('order')->toArray();
    }

    /**
     * Load module from directory
     *
     * @param  string  $dir
     */
    private function loadModule($dir)
    {
        if ( ! $this->isIgnored(basename($dir))) {
            $module = new Module($dir);

            if ( ! $this->isIgnored($module->slug)) {
                $this->put($module->slug, $module);
            }
        }
    }

    /**
     * Get all module slugs
     *
     * @return array
     */
    public function slugs()
    {
        return $this->keys()->toArray();
    }

    /**
     * Get a module by key
     *
     * @param  string  $key
     * @param  mixed   $default
     *
     * @return Module
     */
    public function get($key, $default = null)
    {
        $key = str_slug($key);

        return parent::get($key, $default);
    }

    /**
     * Reset the collection items
     *
     * @return self
     */
    public function reset()
    {
        $this->items = [];

        return $this;
    }

    /**
     * Get enabled modules
     *
     * @return ModulesCollection
     */
    public function enabled()
    {
        return $this->filterByEnabled(true);
    }

    /**
     * Get disabled modules
     *
     * @return ModulesCollection
     */
    public function disabled()
    {
        return $this->filterByEnabled(false);
    }

    /**
     * Filter by the enabled attribute
     *
     * @param  bool  $enabled
     *
     * @return self
     */
    private function filterByEnabled($enabled)
    {
        return $this->filter(function(Module $module) use ($enabled) {
            return $module->enabled === $enabled;
        });
    }

    /**
     * Enable a module
     *
     * @param  string  $name
     *
     * @throws ModuleNotFound
     *
     * @return bool
     */
    public function enable($name)
    {
        return $this->switchEnabled($name, true);
    }

    /**
     * Disable a module
     *
     * @param  string  $name
     *
     * @throws ModuleNotFound
     *
     * @return bool
     */
    public function disable($name)
    {
        return $this->switchEnabled($name, false);
    }

    /**
     * Switch module's enabled attribute
     *
     * @param  string  $name
     * @param  bool    $enable
     *
     * @throws ModuleNotFound
     *
     * @return bool
     */
    private function switchEnabled($name, $enable)
    {
        if ( ! $this->has($name)) {
            throw new ModuleNotFound('Module not found [' . $name . ']');
        }

        $module = $this->get($name);

        return $enable ? $module->enable() : $module->disable();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check if module is ignored
     *
     * @param  string  $name
     *
     * @return bool
     */
    public function isIgnored($name)
    {
        return in_array($name, $this->getIgnored());
    }

    /**
     * Check if module is enabled
     *
     * @param  string  $key
     *
     * @return bool
     */
    public function isEnabled($key)
    {
        if ($this->has($key)) {
            return $this->get($key)->enabled;
        }

        return false;
    }

    /**
     * Check if module is disabled
     *
     * @param  string  $key
     *
     * @return bool
     */
    public function isDisabled($key)
    {
        return ! $this->isEnabled($key);
    }
}
