<?php namespace Arcanedev\Moduly\Commands;

use Arcanedev\Moduly\Bases\Command;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

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

        $module     = $this->argument('module');
        $moduleName = studly_case($module);

        $this->call('module:migrate-reset', [
            'module'     => $module,
            '--database' => $this->option('database'),
            '--force'    => $this->option('force'),
            '--pretend'  => $this->option('pretend'),
        ]);

        $this->call('module:migrate', [
            'module'     => $module,
            '--database' => $this->option('database')
        ]);

        if ($this->needsSeeding()) {
            $this->runSeeder($module, $this->option('database'));
        }

        $this->info(isset($module)
            ? "Module [$moduleName] has been refreshed."
            : "All modules have been refreshed."
        );
    }

    /**
     * Determine if the developer has requested database seeding.
     *
     * @return bool
     */
    protected function needsSeeding()
    {
        return (bool) $this->option('seed');
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
            'module'     => is_null($module) ? $this->argument('module') : $module,
            '--database' => $database
        ]);
    }
}
