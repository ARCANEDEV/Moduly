<?php namespace Arcanedev\Moduly\Commands;

use Arcanedev\Moduly\Bases\Command;
use Arcanedev\Moduly\Moduly;

/**
 * Class SeedCommand
 * @package Arcanedev\Moduly\Commands
 */
class SeedCommand extends Command
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The console command name.
     *
     * @var string $name
     */
    protected $signature    = 'module:seed
                               {module? : Module slug.}
                               {--class= : The class name of the module\'s root seeder.}
                               {--database= : The database connection to seed.}
                               {--force : Force the operation to run while in production.}';

    /**
     * @var string $description The console command description.
     */
    protected $description  = 'Seed the database with records for a specific or all modules';

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

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $module     = $this->argument('module');
        $moduleName = studly_case($module);

        if (isset($module)) {
            if ( ! $this->module->exists($module)) {
                $this->error("Module [$moduleName] does not exist.");

                return;
            }

            if (
                $this->module->isEnabled($module) ||
                $this->option('force')
            ) {
                $this->seed($module);
            }
        }
        else {
            $this->seedAll();
        }
    }

    /**
     * Seed all modules.
     */
    private function seedAll()
    {
        $modules = $this->option('force')
            ? $this->module->all()
            : $this->module->enabled();

        foreach ($modules as $module) {
            $this->seed($module['slug']);
        }
    }

    /**
     * Seed the specific module.
     *
     * @param  string $module
     *
     * @return array
     */
    protected function seed($module)
    {
        $params     = [];
        $moduleName = studly_case($module);
        $namespace  = $this->module->getNamespace();
        $fullPath   = $namespace . $moduleName . '\\Seeds\\' . $moduleName . 'DatabaseSeeder';

        if (class_exists($fullPath, false)) {
            $params['--class'] = $this->option('class')
                ? $this->option('class')
                : $fullPath;

            if ($option = $this->option('database')) {
                $params['--database'] = $option;
            }

            if ($option = $this->option('force')) {
                $params['--force'] = $option;
            }

            $this->call('db:seed', $params);
        }
    }
}
