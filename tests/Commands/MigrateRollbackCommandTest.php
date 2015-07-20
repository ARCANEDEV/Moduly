<?php namespace Arcanedev\Moduly\Tests\Commands;

use Arcanedev\Moduly\Tests\CommandTestCase;

/**
 * Class MigrateRollbackCommandTest
 * @package Arcanedev\Moduly\Tests\Commands
 */
class MigrateRollbackCommandTest extends CommandTestCase
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
    public function it_can_rollback_all_migrations_for_one_module()
    {
        $this->artisan('module:migrate-rollback', [
            'module'    => $this->moduleName,
        ]);
    }

    /** @test */
    public function it_can_rollback_all_migrations_for_all_modules()
    {
        $this->artisan('module:migrate-rollback', [
        ]);
    }
}
