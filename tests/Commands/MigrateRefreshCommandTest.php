<?php namespace Arcanedev\Moduly\Tests\Commands;

use Arcanedev\Moduly\Tests\CommandTestCase;

/**
 * Class MigrateRefreshCommandTest
 * @package Arcanedev\Moduly\Tests\Commands
 */
class MigrateRefreshCommandTest extends CommandTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_refresh_migrations_one_module()
    {
        $this->artisan('module:migrate-refresh', [
            'module'    => $this->moduleName
        ]);
    }
}
