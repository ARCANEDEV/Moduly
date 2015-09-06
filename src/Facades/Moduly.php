<?php namespace Arcanedev\Moduly\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class     Moduly
 *
 * @package  Arcanedev\Moduly\Facades
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Moduly extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'arcanedev.moduly'; }
}
