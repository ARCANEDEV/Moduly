<?php namespace Arcanedev\Moduly\Commands;

use Arcanedev\Moduly\Bases\Command;
use Arcanedev\Moduly\Handlers\ModuleMakeRequestHandler;
use Symfony\Component\Console\Input\InputArgument;

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
     * @var string $name The console command name.
     */
    protected $signature    = 'module:make-request
                               {module : The slug of the module}
                               {name : The name of the class}';

    /**
     * @var string $description The console command description.
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
     * @param ModuleMakeRequestHandler $handler
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
     *
     * @return mixed
     */
    public function handle()
    {
        return $this->handler->fire(
            $this, $this->argument('module'),
            $this->argument('name')
        );
    }
}
