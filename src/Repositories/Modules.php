<?php namespace Arcanedev\Moduly\Repositories;

use Arcanedev\Moduly\Bases\Repository;
use Illuminate\Support\Collection;

/**
 * Class Modules
 * @package Arcanedev\Moduly\Repositories\Local
 */
class Modules extends Repository
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
    * Get all modules.
    *
    * @return Collection
    */
    public function all()
    {
        $modules   = collect();

        $this->getAllBasenames()->each(function($module) use ($modules) {
            $modules->put($module, $this->getProperties($module));
        });

        return $modules->sortBy('order');
    }

    /**
    * Get all module slugs.
    *
    * @return Collection
    */
    public function slugs()
    {
        return $this->all()->keys();
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
        return $this->all()->where($key, $value);
    }

    /**
     * Sort modules by given key in ascending order.
     *
     * @param  string  $key
     * @return Collection
     */
    public function sortBy($key)
    {
        return $this->all()->sortBy($key);
    }

    /**
    * Sort modules by given key in ascending order.
    *
    * @param  string  $key
     *
    * @return Collection
    */
    public function sortByDesc($key)
    {
        return $this->all()->sortByDesc($key);
    }

    /**
     * Determines if the given module exists.
     *
     * @param  string  $slug
     *
     * @return bool
     */
    public function exists($slug)
    {
        return $this->slugs()->contains(str_slug($slug));
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
     * Get a module's properties.
     *
     * @param  string  $slug
     *
     * @return Module|null
     */
    public function getProperties($slug)
    {
        if (is_null($slug)) {
            return null;
        }

        $path   = $this->getManifestPath($slug);
        $module = new Module;

        return ! $this->files->exists($path) ? $module : $module->load($path);
    }

    /**
     * Get a module property value.
     *
     * @param  string  $property
     * @param  mixed   $default
     *
     * @return mixed
     */
    public function getProperty($property, $default = null)
    {
        list($module, $key) = explode('::', $property);

        return $this->getProperties($module)->get($key, $default);
    }

    /**
    * Set the given module property value.
    *
    * @param  string  $property
    * @param  mixed   $value
     *
    * @return bool
    */
    public function setProperty($property, $value)
    {
        list($module, $key) = explode('::', $property);

        $module  = str_slug($module);
        $content = $this->getProperties($module);

        if ($content->isEmpty()) {
            return false;
        }

        if (isset($content[$key])) {
            unset($content[$key]);
        }

        $content[$key] = $value;
        $content       = json_encode($content, JSON_PRETTY_PRINT);

        return $this->files->put($this->getManifestPath($module), $content);
    }

    /**
     * Get all enabled modules.
     *
     * @return Collection
     */
    public function enabled()
    {
        return $this->where('enabled', true);
    }

    /**
     * Get all disabled modules.
     *
     * @return Collection
     */
    public function disabled()
    {
        return $this->where('enabled', false);
    }

    /**
     * Check if specified module is enabled.
     *
     * @param  string  $slug
     *
     * @return bool
     */
    public function isEnabled($slug)
    {
        return $this->getProperty("{$slug}::enabled") === true;
    }

    /**
     * Check if specified module is disabled.
     *
     * @param  string  $slug
     *
     * @return bool
     */
    public function isDisabled($slug)
    {
        return $this->getProperty("{$slug}::enabled") === false;
    }

    /**
     * Enables the specified module.
     *
     * @param  string  $slug
     *
     * @return bool
     */
    public function enable($slug)
    {
        return $this->setProperty("{$slug}::enabled", true);
    }

    /**
     * Disables the specified module.
     *
     * @param  string  $slug
     *
     * @return bool
     */
    public function disable($slug)
    {
        return $this->setProperty("{$slug}::enabled", false);
    }
}
