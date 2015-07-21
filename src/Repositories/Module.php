<?php namespace Arcanedev\Moduly\Repositories;

use Arcanedev\Support\Json;
use Illuminate\Support\Collection;

/**
 * Class Module
 * @package Arcanedev\Moduly\Repositories
 *
 * @property  string  name
 * @property  string  slug
 * @property  string  description
 * @property  string  version
 * @property  bool    enabled
 * @property  bool    enabled
 * @property  int     order
 */
class Module extends Collection
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Module json file
     *
     * @var Json
     */
    protected $json;

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * is utilized for reading data from inaccessible members.
     *
     * @param  $name string
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name, null);
    }

    /**
     * Get module.json path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->json->getPath();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Load Module attributes from json path
     *
     * @param  string $path
     *
     * @return self
     */
    public function load($path)
    {
        $this->json  = Json::make($path);
        $this->items = $this->json->getAttributes();
        if ( ! $this->isEmpty() && ! $this->has('order')) {
            $this->put('order', 9001);
        }

        return $this;
    }

    /**
     * Enable module
     *
     * @return self
     */
    public function enable()
    {
        if ( ! $this->enabled) {
            $this->update([
                'enabled' => true,
            ]);
        }

        return $this;
    }

    /**
     * Disable module
     *
     * @return self
     */
    public function disable()
    {
        if ($this->enabled) {
            $this->update([
                'enabled' => false,
            ]);
        }

        return $this;
    }

    /**
     * Update module.json file
     *
     * @param  array $data
     *
     * @return Module
     */
    protected function update(array $data)
    {
        $this->json->update($data);

        return $this->load($this->json->getPath());
    }

    /* ------------------------------------------------------------------------------------------------
     |  Check Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Check module is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return (bool) $this->enabled;
    }
}
