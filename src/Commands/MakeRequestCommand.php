<?php namespace Arcanedev\Moduly\Commands;

use Arcanedev\Moduly\Bases\Command;
use Arcanedev\Moduly\Handlers\ModuleMakeRequestHandler;

/**
 * Class MakeRequestCommand
 * @package Arcanedev\Moduly\Commands
 */
class MakeRequestCommand extends Command
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
    protected $signature    = 'module:make-request
                               {module : The slug of the module}
                               {name : The name of the class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description  = 'Create a new module form request class';

    /**
     * @var ModuleMakeRequestHandler
     */
    protected $handler;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a new command instance.
     *
     * @param  ModuleMakeRequestHandler  $handler
     */
    public function __construct(ModuleMakeRequestHandler $handler)
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
        return $this->handler->fire(
            $this, $this->getModuleName(),
            $this->getStringArgument('name')
        );
    }
}
