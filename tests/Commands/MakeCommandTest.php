<?php namespace Arcanedev\Moduly\Tests\Commands;

use Arcanedev\Moduly\Tests\CommandTestCase;

/**
 * Class     MakeCommandTest
 *
 * @package  Arcanedev\Moduly\Tests\Commands
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class MakeCommandTest extends CommandTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_run_command()
    {
        $this->assertNotEmpty($this->getModulePath($this->moduleName));
        $this->assertTrue(moduly()->exists($this->moduleName));
        $this->assertTrue(moduly()->isEnabled($this->moduleName));
    }

    /** @test */
    public function it_can_run_command_once()
    {
        $this->artisan('module:make', [
            'module' => $this->moduleName
        ]);
    }
}
