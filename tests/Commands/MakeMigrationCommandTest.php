<?php namespace Arcanedev\Moduly\Tests\Commands;

use Arcanedev\Moduly\Tests\CommandTestCase;

/**
 * Class MakeMigrationCommandTest
 * @package Arcanedev\Moduly\Tests\Commands
 */
class MakeMigrationCommandTest extends CommandTestCase
{
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
            'module'    => 'hello',
            'table'     => 'create_world_table'
        ]);
    }
}
