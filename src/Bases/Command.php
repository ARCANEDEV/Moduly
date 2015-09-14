<?php namespace Arcanedev\Moduly\Bases;

use Arcanedev\Moduly\Moduly;
use Arcanedev\Support\Bases\Command as BaseCommand;

/**
 * Class     Command
 *
 * @package  Arcanedev\Moduly\Bases
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Command extends BaseCommand
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get moduly class
     *
     * @return Moduly
     */
    protected function moduly()
    {
        return $this->laravel['arcanedev.moduly'];
    }

    /**
     * Get module argument
     *
     * @return string
     */
    protected function getModuleName()
    {
        return $this->getStringArgument('module');
    }

    /**
     * Get a string argument
     *
     * @param  string  $name
     *
     * @return string
     */
    protected function getStringArgument($name)
    {
        return (string) $this->argument($name);
    }

    /**
     * Get a boolean option
     *
     * @param  string  $name
     *
     * @return bool
     */
    protected function getBooleanOption($name)
    {
        return (bool) $this->option($name);
    }

    /**
     * Get a string option
     *
     * @param  string  $name
     *
     * @return bool
     */
    protected function getStringOption($name)
    {
        return (string) $this->option($name);
    }
}
