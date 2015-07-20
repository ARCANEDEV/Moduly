<?php namespace Arcanedev\Moduly\Tests\Commands;
use Arcanedev\Moduly\Tests\CommandTestCase;

/**
 * Class MakeCommandTest
 * @package Arcanedev\Moduly\Tests\Commands
 */
class MakeCommandTest extends CommandTestCase
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
    public function it_can_run_command()
    {
        $this->assertNotEmpty($this->getFixtureModulePath($this->moduleName));
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
