<?php namespace Arcanedev\Moduly\Tests\Entities;

use Arcanedev\Moduly\Entities\Module;
use Arcanedev\Moduly\Tests\TestCase;

/**
 * Class     ModuleTest
 *
 * @package  Arcanedev\Moduly\Tests\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ModuleTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Module */
    private $module;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->module = $this->getFixtureModule('foo');
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->module->disable();

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
        $this->assertEquals('foo', $this->module->slug);
        $this->assertEquals('Foo', $this->module->name);
        $this->assertEquals('Foo module description', $this->module->description);
        $this->assertEquals('1.0.0', $this->module->version);
        $this->assertEquals('Arcanedev\\Foo\\FooServiceProvider', $this->module->getProvider());
        $this->assertFalse($this->module->enabled);
        $this->assertFalse($this->module->isEnabled());
        $this->assertEquals(1, $this->module->order);
        $this->assertEquals($this->getModulePath('foo'), $this->module->getPath());
    }

    /** @test */
    public function it_can_enable_and_disable()
    {
        $this->assertFalse($this->module->enabled);
        $this->assertFalse($this->module->isEnabled());

        $this->module->enable();

        $this->assertTrue($this->module->enabled);
        $this->assertTrue($this->module->isEnabled());

        $this->module->disable();

        $this->assertFalse($this->module->enabled);
        $this->assertFalse($this->module->isEnabled());
    }

    /**
     * @test
     *
     * @expectedException \Exception
     */
    public function it_must_throw_exception_when_module_json_file_not_found()
    {
        $this->getFixtureModule('empty');
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\Moduly\Exceptions\ServiceProviderNotFoundException
     * @expectedExceptionMessage Service provider [Arcanedev\Baz\BazServiceProvider] not found in [baz]
     */
    public function it_must_throw_exception_when_module_service_provider_not_found()
    {
        $this->getFixtureModule('baz')->getProvider();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get module fixture
     *
     * @param  string $name
     *
     * @return Module
     */
    private function getFixtureModule($name)
    {
        $path = $this->getModulePath($name);

        return new Module($path);
    }
}
