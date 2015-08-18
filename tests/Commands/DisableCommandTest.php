<?php namespace Arcanedev\Moduly\Tests\Commands;

use Arcanedev\Moduly\Tests\CommandTestCase;

/**
 * Class DisableCommandTest
 * @package Arcanedev\Moduly\Tests\Commands
 */
class DisableCommandTest extends CommandTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_disable_module()
    {
        $this->assertTrue($this->isEnabled());

        $this->artisan('module:disable', [
            'module' => $this->moduleName
        ]);

        $this->assertFalse($this->isEnabled());

        $this->artisan('module:enable', [
            'module' => $this->moduleName
        ]);
    }

    /** @test */
    public function it_cant_disable_not_found_module()
    {
        $this->artisan('module:disable', [
            'module' => 'hello'
        ]);
    }
}
