<?php namespace Arcanedev\Moduly\Commands;

use Arcanedev\Moduly\Bases\Command;
use Arcanedev\Moduly\Handlers\ModuleMakeHandler;

/**
 * Class MakeCommand
 * @package Arcanedev\Moduly\Commands
 */
class MakeCommand extends Command
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
    protected $signature    = 'module:make {module}';

    /**
     * The console command description.
     *
     * @var string $description
     */
    protected $description  = 'Create a new module';

    /**
     * @var ModuleMakeHandler
     */
    protected $handler;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a new command instance.
     *
     * @param ModuleMakeHandler $handler
     */
    public function __construct(ModuleMakeHandler $handler)
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
        $this->handler->fire($this, $this->argument('module'));
    }
}