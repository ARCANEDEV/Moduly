<?php namespace Arcanedev\Moduly\Commands;

use Arcanedev\Moduly\Bases\Command;
use Arcanedev\Moduly\Moduly;
use Illuminate\Console\ConfirmableTrait;
use Illuminate\Database\Migrations\Migrator;

/**
 * Class MigrateCommand
 * @package Arcanedev\Moduly\Commands
 */
class MigrateCommand extends Command
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
    protected $signature    = 'module:migrate
                               {module=all : Module slug.}
                               {--database= : The database connection to use.}
                               {--force : Force the operation to run while in production.}
                               {--pretend : Dump the SQL queries that would be run.}
                               {--seed : Indicates if the seed task should be re-run.}';

    /**
     * The console command description.
     *
     * @var string $description
     */
    protected $description  = 'Run the database migrations for a specific or all modules';

    /**
     * @var Moduly
     */
    protected $module;

    /**
     * The migrator instance.
     *
     * @var Migrator $migrator
     */
    protected $migrator;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a new command instance.
     *
     * @param Migrator  $migrator
     * @param Moduly    $module
     */
    public function __construct(Migrator $migrator, Moduly $module)
    {
        parent::__construct();

        $this->migrator = $migrator;
        $this->module   = $module;
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

        $this->prepareDatabase();

        if ($this->argument('module') == 'all') {
            $this->migrateAll();

            return;
        }

        $module = $this->module->getProperties($this->argument('module'));

        if ( ! is_null($module)) {
            if ($this->module->isEnabled($module['slug'])) {
                $this->migrate($module['slug']);
            }
            elseif ($this->option('force')) {
                $this->migrate($module['slug']);
            }
        }
    }

    /**
     * Run migrations for all modules.
     */
    private function migrateAll()
    {
        $modules = $this->option('force')
            ? $this->module->all()
            : $this->module->enabled();

        foreach ($modules as $module) {
            $this->migrate($module['slug']);
        }
    }

    /**
     * Run migrations for the specified module.
     *
     * @param  string $slug
     */
    protected function migrate($slug)
    {
        $moduleName = studly_case($slug);

        if ( ! $this->module->exists($moduleName)) {
            $this->error("Module [$moduleName] does not exist.");
        }

        $pretend = $this->option('pretend');
        $path    = $this->getMigrationPath($slug);

        $this->migrator->run($path, $pretend);

        // Once the migrator has run we will grab the note output and send it out to
        // the console screen, since the migrator itself functions without having
        // any instances of the OutputInterface contract passed into the class.
        foreach ($this->migrator->getNotes() as $note) {
            if ( ! $this->option('quiet')) {
                $this->output->writeln($note);
            }
        }

        // Finally, if the "seed" option has been given, we will re-run the database
        // seed task to re-populate the database, which is convenient when adding
        // a migration and a seed at the same time, as it is only this command.
        if ($this->option('seed')) {
            $this->call('module:seed', ['module' => $slug, '--force' => true]);
        }
    }

    /**
     * Get migration directory path.
     *
     * @param  string  $slug
     *
     * @return string
     */
    protected function getMigrationPath($slug)
    {
        $path = $this->module->getModulePath($slug);

        return $path . 'Database/Migrations/';
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Prepare the migration database for running.
     */
    private function prepareDatabase()
    {
        $this->migrator->setConnection($this->option('database'));

        if ( ! $this->migrator->repositoryExists()) {
            $this->call('migrate:install', [
                '--database' => $this->option('database'),
            ]);
        }
    }
}
