<?php namespace Arcanedev\Moduly\Commands;

use Arcanedev\Moduly\Bases\Command;
use Illuminate\Console\ConfirmableTrait;

/**
 * Class MigrateRefreshCommand
 * @package Arcanedev\Moduly\Commands
 */
class MigrateRefreshCommand extends Command
{
    /* ------------------------------------------------------------------------------------------------
     |  Traits
     | ------------------------------------------------------------------------------------------------
     */
    use ConfirmableTrait;

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @var string $name The console command name.
     */
    protected $signature = 'module:migrate-refresh
                            {module? : Module slug.}
                            {--database= : The database connection to use.}
                            {--force : Force the operation to run while in production.}
                            {--pretend : Dump the SQL queries that would be run.}
                            {--seed : Indicates if the seed task should be re-run.}';

    /**
     * @var string $description The console command description.
     */
    protected $description = 'Reset and re-run all migrations for a specific or all modules';

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

        $module     = $this->getModuleName();
        $moduleName = studly_case($module);
        $database   = $this->getStringOption('database') ?: null;

        $this->call('module:migrate-reset', [
            'module'     => $module,
            '--database' => $database,
            '--force'    => $this->getBooleanOption('force'),
            '--pretend'  => $this->getBooleanOption('pretend'),
        ]);

        $this->call('module:migrate', [
            'module'     => $module,
            '--database' => $database
        ]);

        if ($this->needsSeeding()) {
            $this->runSeeder($module, $database);
        }

        $this->info(isset($module)
            ? "Module [$moduleName] has been refreshed."
            : 'All modules have been refreshed.'
        );
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Determine if the developer has requested database seeding.
     *
     * @return bool
     */
    protected function needsSeeding()
    {
        return $this->getBooleanOption('seed');
    }

    /**
     * Run the module seeder command.
     *
     * @param  string  $module
     * @param  string  $database
     */
    protected function runSeeder($module = null, $database = null)
    {
        $this->call('module:seed', [
            'module'     => is_null($module) ? $this->getModuleName() : $module,
            '--database' => $database
        ]);
    }
}
