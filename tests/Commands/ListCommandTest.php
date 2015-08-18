<?php namespace Arcanedev\Moduly\Tests\Commands;

use Arcanedev\Moduly\Tests\CommandTestCase;

/**
 * Class ListCommandTest
 * @package Arcanedev\Moduly\Tests\Commands
 */
class ListCommandTest extends CommandTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_list_modules()
    {
        $this->artisan('module:list');
    }
}
