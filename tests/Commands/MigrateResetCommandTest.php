<?php namespace Arcanedev\Moduly\Tests\Commands;

use Arcanedev\Moduly\Tests\CommandTestCase;

/**
 * Class     MigrateResetCommandTest
 *
 * @package  Arcanedev\Moduly\Tests\Commands
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class MigrateResetCommandTest extends CommandTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_reset_migrations_for_one_module()
    {
        $this->artisan('module:migrate-reset', [
            'module'    => $this->moduleName
        ]);
    }

    /** @test */
    public function it_can_reset_migrations_for_all_modules()
    {
        $this->artisan('module:migrate-reset');
    }
}
