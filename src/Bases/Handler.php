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
    protected $module;

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
     * @param Moduly     $module
     * @param Filesystem $finder
     */
    public function __construct(Moduly $module, Filesystem $finder)
    {
        $this->module = $module;
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
