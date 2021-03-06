<?php namespace Arcanedev\Moduly;

use Arcanedev\Support\PackageServiceProvider as ServiceProvider;

/**
 * Class     ModulyServiceProvider
 *
 * @package  Arcanedev\Moduly
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ModulyServiceProvider extends ServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Vendor name.
     *
     * @var string
     */
    protected $vendor = 'arcanedev';

    /**
     * Package name.
     *
     * @var string
     */
    protected $package = 'moduly';

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the base path of the package.
     *
     * @return string
     */
    public function getBasePath()
    {
        return dirname(__DIR__);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerConfig();
        $this->registerModuly();
        $this->registerProviders();

        $this->app['arcanedev.moduly']->register();
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
     * Register Moduly
     */
    private function registerModuly()
    {
        $this->app->bind('arcanedev.moduly', function($app) {
            return new Moduly($app);
        });

        $this->addFacade('Moduly', Facades\Moduly::class);
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
