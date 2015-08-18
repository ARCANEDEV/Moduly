<?php namespace Arcanedev\Moduly\Contracts;

use Arcanedev\Moduly\Bases\Collection;
use Arcanedev\Moduly\Entities\ModulesCollection;

/**
 * Interface ModulyInterface
 * @package Arcanedev\Moduly\Contracts
 */
interface ModulyInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register all modules
     */
    public function register();

    /**
     * Get all modules
     *
     * @return ModulesCollection
     */
    public function all();

    /**
     * Get all module slugs
     *
     * @return array
     */
    public function slugs();

    /**
     * Get modules based on where clause.
     *
     * @param  string  $key
     * @param  mixed   $value
     *
     * @return Collection
     */
    public function where($key, $value);

    /**
     * Check if the given module exists.
     *
     * @param  string  $name
     *
     * @return bool
     */
    public function exists($name);

    /**
     * Returns count of all modules.
     *
     * @return int
     */
    public function count();

    /**
     * Gets all enabled modules.
     *
     * @return ModulesCollection
     */
    public function enabled();

    /**
     * Gets all disabled modules.
     *
     * @return ModulesCollection
     */
    public function disabled();
}
