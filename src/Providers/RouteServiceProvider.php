<?php namespace Arcanedev\Moduly\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseRouteServiceProvider;

/**
 * Class RouteServiceProvider
 * @package Arcanedev\Moduly\Providers
 */
abstract class RouteServiceProvider extends BaseRouteServiceProvider
{
    /**
     * Set the root controller namespace for the application.
     *
     * @return void
     */
    protected function setRootControllerNamespace()
    {
        // Intentionally left empty to prevent overwriting the
        // root controller namespace.
    }
}
