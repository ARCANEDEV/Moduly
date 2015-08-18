<?php namespace Arcanedev\Foo;

use Arcanedev\Support\Laravel\ServiceProvider;

/**
 * Class FooServiceProvider
 * @package Arcanedev\Foo
 */
class FooServiceProvider extends ServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    protected $package = 'foo';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }
}
