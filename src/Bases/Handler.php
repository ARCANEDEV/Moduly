<?php namespace Arcanedev\Moduly\Bases;

use Arcanedev\Moduly\Moduly;
use Illuminate\Filesystem\Filesystem;

/**
 * Class Handler
 * @package Arcanedev\Moduly\Bases
 */
abstract class Handler
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @var Moduly
     */
    protected $moduly;

    /**
     * @var Filesystem
     */
    protected $finder;

    /**
     * @var Command
     */
    protected $command;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Constructor method.
     *
     * @param  Moduly      $moduly
     * @param  Filesystem  $finder
     */
    public function __construct(Moduly $moduly, Filesystem $finder)
    {
        $this->moduly = $moduly;
        $this->finder = $finder;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Set console Command
     *
     * @param  Command  $command
     *
     * @return self
     */
    public function setCommand($command)
    {
        $this->command = $command;

        return $this;
    }
}
