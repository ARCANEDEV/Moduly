<?php namespace Arcanedev\Moduly\Tests\Commands;

use Arcanedev\Moduly\Tests\CommandTestCase;

/**
 * Class SeedCommandTest
 * @package Arcanedev\Moduly\Tests\Commands
 */
class SeedCommandTest extends CommandTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

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
