<?php namespace Arcanedev\Moduly\Commands;

use Arcanedev\Moduly\Bases\Command;
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
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:list';

    /**
     * @var string $description The console command description.
     */
    protected $description = 'List all application modules';

    /**
     * @var Moduly
     */
    protected $moduly;

    /**
     * @var array $header The table headers for the command.
     */
    protected $headers = ['#', 'Name', 'Slug', 'Description', 'Status'];

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
            $this->error('Your application doesn\'t have any modules.');
        }
    }

    /**
     * Display the module information on the console.
     *
     * @param  Collection $modules
     */
    protected function displayModules($modules)
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
    protected function prepareModules($modules)
    {
        $results = [];

        foreach ($modules as $module) {
            $results[] = $this->getModuleInformation($module);
        }

        return array_filter($results);
    }

    /**
     * Returns module manifest information.
     *
     * @param  string $module
     *
     * @return array
     */
    protected function getModuleInformation($module)
    {
        return [
            '#'           => $module['order'],
            'name'        => $module['name'],
            'slug'        => $module['slug'],
            'description' => $module['description'],
            'status'      => ($module['enabled']) ? 'Enabled' : 'Disabled'
        ];
    }
}
