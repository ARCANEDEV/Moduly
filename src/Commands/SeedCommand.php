<?php namespace Arcanedev\Moduly\Commands;

use Arcanedev\Moduly\Bases\Command;
use Arcanedev\Moduly\Moduly;

/**
 * Class     SeedCommand
 *
 * @package  Arcanedev\Moduly\Commands
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SeedCommand extends Command
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The signature of the console command.
     *
     * @var string
     */
    protected $signature    = 'module:seed
                               {module? : Module slug.}
                               {--class= : The class name of the module\'s root seeder.}
                               {--database= : The database connection to seed.}
                               {--force : Force the operation to run while in production.}';

    /**
     * The console command description.
     *
     * @var string
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
     * @param  Moduly  $module
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
        $module     = $this->getModuleName();
        $moduleName = studly_case($module);
        $force      = $this->getBooleanOption('force');

        if (isset($module)) {
            if ( ! $this->module->exists($module)) {
                $this->error("Module [$moduleName] does not exist.");
            }
            elseif ($this->module->isEnabled($module) || $force) {
                $this->seed($module, $force);
            }
        }
        else {
            $this->seedAll($force);
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Seed all modules.
     *
     * @param  bool  $force
     */
    private function seedAll($force = false)
    {
        $modules = $force ? $this->module->all() : $this->module->enabled();

        foreach ($modules as $module) {
            $this->seed($module['slug'], $force);
        }
    }

    /**
     * Seed the specific module.
     *
     * @param  string  $module
     * @param  bool    $force
     */
    protected function seed($module, $force = false)
    {
        $params     = [];
        $moduleName = studly_case($module);
        $namespace  = $this->module->getNamespace();
        $fullPath   = $namespace . $moduleName . '\\Seeds\\' . $moduleName . 'DatabaseSeeder';

        if ( ! class_exists($fullPath, false)) {
            return;
        }

        $params['--class'] = $this->getStringOption('class')
            ? $this->getStringOption('class')
            : $fullPath;

        if ($this->getStringOption('database')) {
            $params['--database'] = $this->getStringOption('database');
        }

        if ($force) {
            $params['--force'] = $force;
        }

        $this->call('db:seed', $params);
    }
}
