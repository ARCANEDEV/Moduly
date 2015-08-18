<?php namespace Arcanedev\Moduly\Providers;

use Arcanedev\Moduly\Commands\DisableCommand;
use Arcanedev\Moduly\Commands\EnableCommand;
use Arcanedev\Moduly\Commands\ListCommand;
use Arcanedev\Moduly\Commands\MakeCommand;
use Arcanedev\Moduly\Commands\MakeMigrationCommand;
use Arcanedev\Moduly\Commands\MakeRequestCommand;
use Arcanedev\Moduly\Commands\MigrateCommand;
use Arcanedev\Moduly\Commands\MigrateRefreshCommand;
use Arcanedev\Moduly\Commands\MigrateResetCommand;
use Arcanedev\Moduly\Commands\MigrateRollbackCommand;
use Arcanedev\Moduly\Commands\SeedCommand;
use Arcanedev\Moduly\Handlers\ModuleMakeHandler;
use Arcanedev\Moduly\Handlers\ModuleMakeMigrationHandler;
use Arcanedev\Moduly\Handlers\ModuleMakeRequestHandler;
use Arcanedev\Support\Laravel\ServiceProvider;
use Closure;

/**
 * Class CommandsServiceProvider
 * @package Arcanedev\Moduly\Providers
 */
class CommandsServiceProvider extends ServiceProvider
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const COMMAND_KEY = 'module';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    protected $commands = [];

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /* ------------------------------------------------------------------------------------------------
    |  Main Functions
    | ------------------------------------------------------------------------------------------------
    */
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerMakeCommand();
        $this->registerEnableCommand();
        $this->registerDisableCommand();
        $this->registerMakeMigrationCommand();
        $this->registerMakeRequestCommand();
        $this->registerMigrateCommand();
        $this->registerMigrateRefreshCommand();
        $this->registerMigrateResetCommand();
        $this->registerMigrateRollbackCommand();
        $this->registerSeedCommand();
        $this->registerListCommand();

        $this->commands($this->commands);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return $this->commands;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the "module:enable" console command.
     *
     * @return EnableCommand
     */
    protected function registerEnableCommand()
    {
        $this->registerCommand('enable', function() {
            return new EnableCommand;
        });
    }

    /**
     * Register the "module:disable" console command.
     *
     * @return DisableCommand
     */
    protected function registerDisableCommand()
    {
        $this->registerCommand('disable', function() {
            return new DisableCommand;
        });
    }

    /**
     * Register the "module:make" console command.
     */
    protected function registerMakeCommand()
    {
        $this->registerCommand('make', function($app) {
            return new MakeCommand(
                new ModuleMakeHandler($app['arcanedev.moduly'], $app['files'])
            );
        });
    }

    /**
     * Register the "module:make:migration" console command.
     */
    protected function registerMakeMigrationCommand()
    {
        $this->registerCommand('make-migration', function($app) {
            return new MakeMigrationCommand(
                new ModuleMakeMigrationHandler($app['arcanedev.moduly'], $app['files'])
            );
        });
    }

    /**
     * Register the "module:make:request" console command.
     */
    protected function registerMakeRequestCommand()
    {
        $this->registerCommand('make-request', function($app) {
            return new MakeRequestCommand(
                new ModuleMakeRequestHandler($app['arcanedev.moduly'], $app['files'])
            );
        });
    }

    /**
     * Register the "module:migrate" console command.
     */
    protected function registerMigrateCommand()
    {
        $this->registerCommand('migrate', function($app) {
            return new MigrateCommand($app['migrator'], $app['arcanedev.moduly']);
        });
    }

    /**
     * Register the "module:migrate:refresh" console command.
     */
    protected function registerMigrateRefreshCommand()
    {
        $this->registerCommand('migrate-refresh', function() {
            return new MigrateRefreshCommand;
        });
    }

    /**
     * Register the "module:migrate:reset" console command.
     */
    protected function registerMigrateResetCommand()
    {
        $this->registerCommand('migrate-reset', function($app) {
            return new MigrateResetCommand($app['arcanedev.moduly'], $app['files'], $app['migrator']);
        });
    }

    /**
     * Register the "module:migrate:rollback" console command.
     */
    protected function registerMigrateRollbackCommand()
    {
        $this->registerCommand('migrate-rollback', function($app) {
            return new MigrateRollbackCommand($app['arcanedev.moduly']);
        });
    }

    /**
     * Register the "module:seed" console command.
     */
    protected function registerSeedCommand()
    {
        $this->registerCommand('seed', function($app) {
            return new SeedCommand($app['arcanedev.moduly']);
        });
    }

    /**
     * Register the "module:list" console command.
     */
    protected function registerListCommand()
    {
        $this->registerCommand('list', function($app) {
            return new ListCommand($app['arcanedev.moduly']);
        });
    }

    /**
     * Register a command
     *
     * @param  string   $name
     * @param  Closure  $callback
     *
     * @return self
     */
    private function registerCommand($name, Closure $callback)
    {
        $name = self::COMMAND_KEY . '.' . $name;
        $this->app->bind($name, $callback);
        $this->commands[] = $name;

        return $this;
    }
}
