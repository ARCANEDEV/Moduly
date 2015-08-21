<?php namespace Arcanedev\Moduly\Commands;

use Arcanedev\Moduly\Bases\Command;
use Arcanedev\Moduly\Moduly;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Filesystem\Filesystem;

/**
 * Class MigrateResetCommand
 * @package Arcanedev\Moduly\Commands
 */
class MigrateResetCommand extends Command
{
    /* ------------------------------------------------------------------------------------------------
     |  Trait
     | ------------------------------------------------------------------------------------------------
     */
    use ConfirmableTrait;

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The signature of the console command.
     *
     * @var string
     */
    protected $signature    = 'module:migrate-reset
                               {module? : Module slug.}
                               {--database= : The database connection to use.}
                               {--force : Force the operation to run while in production.}
                               {--pretend : Dump the SQL queries that would be run.}
                               {--seed : Indicates if the seed task should be re-run.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description  = 'Rollback all database migrations for a specific or all modules';

    /**
     * @var Moduly
     */
    protected $module;

    /**
     * @var Migrator
     */
    protected $migrator;

    /**
     * @var Filesystem
     */
    protected $files;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a new command instance.
     *
     * @param Moduly      $module
     * @param Filesystem  $files
     * @param Migrator    $migrator
     */
    public function __construct(Moduly $module, Filesystem $files, Migrator $migrator)
    {
        parent::__construct();

        $this->module   = $module;
        $this->files    = $files;
        $this->migrator = $migrator;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ( ! $this->confirmToProceed()) {
            return;
        }

        $module = $this->getModuleName();
        $force  = $this->getBooleanOption('force');

        if (empty($module)) {
            $this->resetAll($force);
        }
        elseif ($this->module->isEnabled($module) || $force) {
            $this->reset($module);
        }
    }

    /**
     * Run the migration reset for all modules.
     *
     * @param bool  $force
     */
    private function resetAll($force = false)
    {
        $modules = $force ? $this->module->all() : $this->module->enabled();
        $modules = $modules->reverse();

        foreach ($modules as $module) {
            $this->reset($module->name);
        }
    }

    /**
     * Run the migration reset for the specified module.
     *
     * Migrations should be reset in the reverse order that they were
     * migrated up as. This ensures the database is properly reversed
     * without conflict.
     *
     * @param  string  $slug
     */
    protected function reset($slug)
    {
        if ($this->getStringOption('database')) {
            $this->migrator->setconnection($this->getStringOption('database'));
        }

        $pretend       = $this->getBooleanOption('pretend');
        $migrationPath = $this->getMigrationPath($slug);
        $migrations    = array_reverse($this->migrator->getMigrationFiles($migrationPath));

        if (count($migrations)) {
            foreach ($migrations as $migration) {
                $this->info('Migration: ' . $migration);
                $this->runDown($slug, $migration, $pretend);
            }
        }
        else {
            $this->error('Nothing to rollback.');
        }
    }

    /**
     * Run "down" a migration instance.
     *
     * @param  string $slug
     * @param  object $migration
     * @param  bool   $pretend
     */
    protected function runDown($slug, $migration, $pretend)
    {
        $migrationPath = $this->getMigrationPath($slug);
        $file          = $migrationPath . '/' . $migration . '.php';
        $classFile     = implode('_', array_slice(explode('_', str_replace('.php', '', $file)), 4));
        $class         = studly_case($classFile);
        $table         = $this->laravel['config']['database.migrations'];

        include($file);

        $instance = new $class;
        $instance->down();

        $this->laravel['db']
            ->table($table)
            ->where('migration', $migration)
            ->delete();
    }

    /**
     * Get the console command parameters.
     *
     * @param  string $slug
     * @return array
     */
    protected function getParameters($slug)
    {
        $params = [];

        $params['--path'] = $this->getMigrationPath($slug);

        if ($this->getStringOption('database')) {
            $params['--database'] = $this->getStringOption('database');
        }

        if ($this->getBooleanOption('pretend')) {
            $params['--pretend'] = true;
        }

        if ($this->getBooleanOption('seed')) {
            $params['--seed'] = true;
        }

        return $params;
    }

    /**
     * Get migrations path.
     *
     * @return string
     */
    protected function getMigrationPath($slug)
    {
        return $this->module->getModulePath($slug) . config('moduly.folders.migrations');
    }
}
