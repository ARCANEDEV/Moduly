<?php namespace Arcanedev\Moduly\Commands;

use Arcanedev\Moduly\Bases\Command;
use Arcanedev\Moduly\Handlers\ModuleMakeMigrationHandler;

/**
 * Class MakeMigrationCommand
 * @package Arcanedev\Moduly\Commands
 */
class MakeMigrationCommand extends Command
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @var string $name The console command name.
     */
    protected $signature    = 'module:make-migration {module} {table}';

    /**
     * @var string $description The console command description.
     */
    protected $description  = 'Create a new module migration file';

    /**
     * @var ModuleMakeMigrationHandler
     */
    protected $handler;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a new command instance.
     *
     * @param ModuleMakeMigrationHandler $handler
     */
    public function __construct(ModuleMakeMigrationHandler $handler)
    {
        parent::__construct();

        $this->handler = $handler;
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
        $this->handler->fire(
            $this,
            $this->getModuleName(),
            $this->getStringArgument('table')
        );
    }
}
