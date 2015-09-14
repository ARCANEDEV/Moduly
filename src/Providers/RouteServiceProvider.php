<?php namespace Arcanedev\Moduly\Providers;

use Arcanedev\Support\Providers\RouteServiceProvider as BaseRouteServiceProvider;

/**
 * Class     RouteServiceProvider
 *
 * @package  Arcanedev\Moduly\Providers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class RouteServiceProvider extends BaseRouteServiceProvider
{
    /**
     * Set the root controller namespace for the application.
     */
    protected function setRootControllerNamespace()
    {
        // Intentionally left empty to prevent overwriting the
        // root controller namespace.
    }
}
