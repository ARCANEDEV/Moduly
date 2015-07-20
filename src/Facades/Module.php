<?php namespace Arcanedev\Moduly\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Module
 * @package Arcanedev\Moduly\Facades
 */
class Module extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'arcanedev.moduly'; }
}
