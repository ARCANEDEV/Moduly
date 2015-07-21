<?php namespace Arcanedev\Moduly\Tests\Repositories;

use Arcanedev\Moduly\Repositories\Module;
use Arcanedev\Moduly\Tests\TestCase;

/**
 * Class ModuleTest
 * @package Arcanedev\Moduly\Tests\Repositories
 */
class ModuleTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @var Module
     */
    protected $module;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->module = new Module;
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->module);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(Module::class, $this->module);
        $this->assertTrue($this->module->isEmpty());
    }

    /** @test */
    public function it_can_load_json_file()
    {
        $this->loadModule();

        $this->assertFalse($this->module->isEmpty());
        $this->assertEquals('Test', $this->module->name);
        $this->assertEquals('test', $this->module->slug);
        $this->assertEquals('1.0.0', $this->module->version);
        $this->assertEquals('This is the description for the Test module.', $this->module->description);
        $this->assertTrue($this->module->enabled);
        $this->assertEquals(9001, $this->module->order);
    }

    /** @test */
    public function it_can_enable_and_disable_module()
    {
        $this->loadModule();

        $this->module->disable();

        $this->assertFalse($this->module->isEnabled());

        $this->module->enable();

        $this->assertTrue($this->module->isEnabled());
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Function
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Load module
     *
     * @return Module
     */
    public function loadModule()
    {
        $path = __DIR__ . '/../fixture/files/module.json';

        return $this->module = (new Module)->load($path);
    }
}
