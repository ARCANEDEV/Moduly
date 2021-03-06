<?php namespace Arcanedev\Moduly\Commands;

use Arcanedev\Moduly\Bases\Command;

/**
 * Class     EnableCommand
 *
 * @package  Arcanedev\Moduly\Commands
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class EnableCommand extends Command
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
    protected $signature    = 'module:enable {module}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description  = 'Enable a module';

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

        if ( ! $this->moduly()->isEnabled($module)) {
            $this->moduly()->enable($module);
            $this->info("Module [{$module}] was enabled successfully.");
        }
        else {
            $this->comment("Module [{$module}] is already enabled.");
        }
    }
}
