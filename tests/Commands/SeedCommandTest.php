<?php namespace Arcanedev\Moduly\Tests\Commands;

use Arcanedev\Moduly\Tests\CommandTestCase;

/**
 * Class     SeedCommandTest
 *
 * @package  Arcanedev\Moduly\Tests\Commands
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class SeedCommandTest extends CommandTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_seed_one_module()
    {
        $this->artisan('module:seed', [
            'module'    => $this->moduleName
        ]);
    }

    /** @test */
    public function it_can_seed_all_modules()
    {
        $this->artisan('module:seed');
    }
}
