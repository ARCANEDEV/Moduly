<?php namespace Arcanedev\Moduly\Commands;

use Arcanedev\Moduly\Bases\Command;
use Arcanedev\Moduly\Entities\Module;
use Arcanedev\Moduly\Moduly;
use Illuminate\Support\Collection;

/**
 * Class ListCommand
 * @package Arcanedev\Moduly\Commands
 */
class ListCommand extends Command
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
    protected $signature = 'module:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all application modules';

    /**
     * The table headers for the command.
     *
     * @var array
     */
    protected $headers = ['#', 'Slug', 'Name', 'Description', 'Status'];

    /**
     * @var Moduly
     */
    protected $moduly;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a new command instance.
     *
     * @param Moduly $module
     */
    public function __construct(Moduly $module)
    {
        parent::__construct();

        $this->moduly = $module;
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
        $modules = $this->moduly->all();

        if ($modules->count()) {
            $this->displayModules($modules);
        }
        else {
            // @codeCoverageIgnoreStart
            $this->error("Your application doesn't have any modules.");
            // @codeCoverageIgnoreEnd
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Display the module information on the console.
     *
     * @param  Collection $modules
     */
    private function displayModules($modules)
    {
        $this->table($this->headers, $this->prepareModules($modules));
    }

    /**
     * Get all modules.
     *
     * @param  Collection $modules
     *
     * @return array
     */
    private function prepareModules($modules)
    {
        $results = array_map(function($module) {
            return $this->getModuleInformation($module);
        }, $modules->toArray());

        return array_filter($results);
    }

    /**
     * Returns module manifest information.
     *
     * @param  Module $module
     *
     * @return array
     */
    private function getModuleInformation(Module $module)
    {
        return [
            '#'           => $module->order,
            'slug'        => $module->slug,
            'name'        => $module->name,
            'description' => $module->description,
            'status'      => $module->isEnabled() ? 'Enabled' : 'Disabled'
        ];
    }
}
