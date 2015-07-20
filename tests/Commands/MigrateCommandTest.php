<?php namespace Arcanedev\Moduly\Tests\Commands;

use Arcanedev\Moduly\Tests\CommandTestCase;

/**
 * Class MigrateCommandTest
 * @package Arcanedev\Moduly\Tests\Commands
 */
class MigrateCommandTest extends CommandTestCase
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
    public function it_can_migrate()
    {
        $this->artisan('module:migrate', [
            'module' => $this->moduleName
        ]);
    }

    /** @test */
    public function it_can_migrate_all()
    {
        $this->artisan('module:migrate');
    }
}
