<?php namespace Arcanedev\Moduly\Bases;

use Arcanedev\Moduly\Moduly;
use Illuminate\Console\Command as IlluminateCommand;

abstract class Command extends IlluminateCommand
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
}
