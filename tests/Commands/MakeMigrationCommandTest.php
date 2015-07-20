<?php namespace Arcanedev\Moduly\Tests\Commands;

use Arcanedev\Moduly\Tests\CommandTestCase;

/**
 * Class MakeMigrationCommandTest
 * @package Arcanedev\Moduly\Tests\Commands
 */
class MakeMigrationCommandTest extends CommandTestCase
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
    public function it_can_make_migration_for_the_module()
    {
        $this->artisan('module:make-migration', [
            'module'    => $this->moduleName,
            'table'     => 'create_foo_table'
        ]);
    }

    /** @test */
    public function it_cant_make_migration_if_module_not_found()
    {
        $this->artisan('module:make-migration', [
            'module'    => 'bar',
            'table'     => 'create_foo_table'
        ]);
    }
}
