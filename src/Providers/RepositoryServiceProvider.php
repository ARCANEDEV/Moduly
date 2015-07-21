<?php namespace Arcanedev\Moduly\Providers;

use Arcanedev\Moduly\Contracts\ModuleRepositoryInterface;
use Arcanedev\Moduly\Repositories\Modules;
use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * @package Arcanedev\Moduly\Providers
 */
class RepositoryServiceProvider extends ServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
    * @var bool $defer Indicates if loading of the provider is deferred.
    */
    protected $defer = false;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $driver = ucfirst(config('moduly.driver'));

        $namespace = ($driver === 'Custom')
            ? config('moduly.custom-driver')
            : Modules::class;

        $this->app->bind(ModuleRepositoryInterface::class, $namespace);
    }
}
