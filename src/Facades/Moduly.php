<?php namespace Arcanedev\Moduly\Facades;

use Arcanedev\Moduly\ModulyServiceProvider;
use Illuminate\Support\Facades\Facade;

/**
 * Class Moduly
 * @package Arcanedev\Moduly\Facades
 */
class Moduly extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return ModulyServiceProvider::MODULY_KEY; }
}
