<?php namespace Arcanedev\Moduly\Tests\Commands;

use Arcanedev\Moduly\Tests\CommandTestCase;

/**
 * Class     MigrateCommandTest
 *
 * @package  Arcanedev\Moduly\Tests\Commands
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class MigrateCommandTest extends CommandTestCase
{
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
