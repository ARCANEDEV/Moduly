<?php namespace Arcanedev\Moduly\Commands;

use Arcanedev\Moduly\Bases\Command;

/**
 * Class DisableCommand
 * @package Arcanedev\Moduly\Commands
 */
class DisableCommand extends Command
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
    protected $signature    = 'module:disable {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description  = 'Disable a module';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $module = $this->getModuleName();

        if ($this->moduly()->isEnabled($module)) {
            $this->moduly()->disable($module);
            $this->info("Module [{$module}] was disabled successfully.");
        }
        else {
            $this->comment("Module [{$module}] is already disabled.");
        }
    }
}
