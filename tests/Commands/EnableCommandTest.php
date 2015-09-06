<?php namespace Arcanedev\Moduly\Tests\Commands;

use Arcanedev\Moduly\Tests\CommandTestCase;

/**
 * Class     EnableCommandTest
 *
 * @package  Arcanedev\Moduly\Tests\Commands
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class EnableCommandTest extends CommandTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->artisan('module:disable', [
            'module' => $this->moduleName
        ]);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_enable_module()
    {
        $this->assertFalse($this->isEnabled());

        $this->artisan('module:enable', [
            'module' => $this->moduleName
        ]);

        $this->assertTrue($this->isEnabled());
    }

    /** @test */
    public function it_can_enable_module_once()
    {
        $this->assertFalse($this->isEnabled());

        for($i = 0; $i < 2; $i++) {
            $this->artisan('module:enable', [
                'module' => $this->moduleName
            ]);

            $this->assertTrue($this->isEnabled());
        }
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\Moduly\Exceptions\ModuleNotFound
     * @expectedExceptionMessage Module not found [hello]
     */
    public function it_cant_enable_not_found_module()
    {
        $this->artisan('module:enable', [
            'module' => 'hello'
        ]);
    }
}
