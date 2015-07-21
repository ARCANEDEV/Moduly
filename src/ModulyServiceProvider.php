<?php namespace Arcanedev\Moduly;

use Arcanedev\Moduly\Contracts\ModuleRepositoryInterface;
use Arcanedev\Moduly\Providers\CommandsServiceProvider;
use Arcanedev\Moduly\Providers\MigrationServiceProvider;
use Arcanedev\Moduly\Providers\RepositoryServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

/**
 * Class ModulyServiceProvider
 * @package Arcnaedev\Moduly
 */
class ModulyServiceProvider extends ServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Contants
     | ------------------------------------------------------------------------------------------------
     */
    const MODULY_KEY = 'arcanedev.moduly';

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
        $this->publishes([
            __DIR__ . '/../config/moduly.php' => config_path('moduly.php'),
        ]);

        moduly()->register();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/moduly.php', 'moduly'
        );

        $this->app->register(RepositoryServiceProvider::class);
        $this->app->register(MigrationServiceProvider::class);
        $this->app->register(CommandsServiceProvider::class);

        $this->app->singleton(self::MODULY_KEY, function (Application $app) {
            return new Moduly($app, $app->make(ModuleRepositoryInterface::class));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string
     */
    public function provides()
    {
        return [self::MODULY_KEY];
    }
}
