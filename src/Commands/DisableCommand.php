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
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:disable {module}';

    /**
     * @var string $description The console command description.
     */
    protected $description = 'Disable a module';

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

    /**
     * Get module argument
     *
     * @return string
     */
    protected function getModuleName()
    {
        return (string) $this->argument('module');
    }
}
