<?php namespace Arcanedev\Moduly\Commands;

use Arcanedev\Moduly\Bases\Command;
use Arcanedev\Moduly\Moduly;
use Arcanedev\Moduly\Traits\MigrationTrait;
use Illuminate\Console\ConfirmableTrait;

/**
 * Class MigrateRollbackCommand
 * @package Arcanedev\Moduly\Commands
 */
class MigrateRollbackCommand extends Command
{
    /* ------------------------------------------------------------------------------------------------
     |  Traits
     | ------------------------------------------------------------------------------------------------
     */
    use MigrationTrait, ConfirmableTrait;

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The console command name.
     *
     * @var string $name
     */
    protected $signature    = 'module:migrate-rollback
                               {module? : Module slug.}
                               {--database= : The database connection to use.}
                               {--force : Force the operation to run while in production.}
                               {--pretend : Dump the SQL queries that would be run.}';

    /**
     * The console command description.
     *
     * @var string $description
     */
    protected $description  = 'Rollback the last database migrations for a specific or all modules';

    /**
     * @var Moduly
     */
    protected $module;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a new command instance.
     *
     * @param Moduly  $module
     */
    public function __construct(Moduly $module)
    {
        parent::__construct();

        $this->module = $module;
    }

    /**
     * Execute the console command.
     */
    public function fire()
    {
        if ( ! $this->confirmToProceed()) {
            return;
        }

        $module = $this->argument('module');

        if ($module) {
            $this->rollback($module);
        }
        else {
            $this->rollbackAll();
        }
    }

    /**
     * Run the migration rollback for all modules.
     */
    public function rollbackAll()
    {
        foreach ($this->module->all() as $module) {
            $this->rollback($module['slug']);
        }
    }

    /**
     * Run the migration rollback for the specified module.
     *
     * @param  string  $slug
     */
    protected function rollback($slug)
    {
        $this->requireMigrations(studly_case($slug));
        $this->call('migrate:rollback', [
            '--database' => $this->option('database'),
            '--force'    => $this->option('force'),
            '--pretend'  => $this->option('pretend'),
        ]);
    }
}
