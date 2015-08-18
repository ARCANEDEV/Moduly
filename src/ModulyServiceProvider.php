<?php namespace Arcanedev\Moduly;

use Arcanedev\Support\Laravel\PackageServiceProvider;

/**
 * Class ModulyServiceProvider
 * @package Arcanedev\Moduly
 */
class ModulyServiceProvider extends PackageServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    protected $package = 'moduly';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerConfigs();
        $this->registerServices();
        $this->registerFacades();
        $this->registerProviders();

        moduly()->register();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['arcanedev.moduly'];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register services
     */
    private function registerServices()
    {
        $this->app->bind(Moduly::KEY_NAME, function($app) {
            return new Moduly($app, $app['config']);
        });
    }

    /**
     * Register facades
     */
    private function registerFacades()
    {
        $this->addAliases([
            'Moduly' => Facades\Moduly::class
        ]);
    }

    /**
     * Register providers
     */
    private function registerProviders()
    {
        $this->app->register(Providers\MigrationServiceProvider::class);
        $this->app->register(Providers\CommandsServiceProvider::class);
    }
}
