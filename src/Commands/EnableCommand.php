<?php namespace Arcanedev\Moduly\Commands;

use Arcanedev\Moduly\Bases\Command;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class EnableCommand
 * @package Arcanedev\Moduly\Commands
 */
class EnableCommand extends Command
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
    protected $signature = 'module:enable {module}';

    /**
     * @var string $description The console command description.
     */
    protected $description = 'Enable a module';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $module = $this->argument('module');

        if ($this->moduly()->isEnabled($module)) {
            $this->comment("Module [{$module}] is already enabled.");

            return;
        }

        $this->moduly()->enable($module);
        $this->info("Module [{$module}] was enabled successfully.");
    }
}
