<?php namespace Arcanedev\Moduly\Tests\Entities;

use Arcanedev\Moduly\Entities\Module;
use Arcanedev\Moduly\Entities\ModulesCollection;
use Arcanedev\Moduly\Tests\TestCase;

/**
 * Class     ModulesCollectionTest
 *
 * @package  Arcanedev\Moduly\Tests\Entities
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ModulesCollectionTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var ModulesCollection */
    private $modules;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->modules = new ModulesCollection;
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->modules);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(ModulesCollection::class, $this->modules);
        $this->assertCount(0, $this->modules);
        $this->assertCount(0, $this->modules->slugs());
        $this->assertCount(0, $this->modules->enabled());
        $this->assertCount(0, $this->modules->disabled());
    }

    /** @test */
    public function it_can_load_modules()
    {
        $this->loadModules();
        $this->assertCount(3, $this->modules);
    }

    /** @test */
    public function it_can_get_one_module()
    {
        $this->loadModules();

        $exceptedData = [
            'foo' => [
                'name' => 'Foo',
            ],
            'Foo' => [
                'name' => 'Foo',
            ],
            'bar' => [
                'name' => 'Bar',
            ],
        ];

        foreach($exceptedData as $key => $excepted) {
            $module = $this->modules->get($key);
            $this->assertInstanceOf(Module::class, $module);
            $this->assertEquals(str_slug($excepted['name']), $module->slug);
            $this->assertEquals($excepted['name'], $module->name);
        }
    }

    /** @test */
    public function it_can_get_only_enabled_modules()
    {
        $this->loadModules();

        $this->assertCount(1, $this->modules->enabled());
    }

    /** @test */
    public function it_can_get_only_disabled_modules()
    {
        $this->loadModules();

        $this->assertCount(2, $this->modules->disabled());
    }

    /** @test */
    public function it_can_reset()
    {
        $this->loadModules();

        $this->assertCount(3, $this->modules->all());

        $this->modules->reset();

        $this->assertCount(0, $this->modules->all());
    }

    /** @test */
    public function it_can_check_if_module_is_enabled()
    {
        $this->loadModules();

        $exceptedData = [
            'foo'   => false,
            'bar'   => true,
            'baz'   => false,
            'hello' => false,
        ];

        foreach($exceptedData as $key => $excepted) {
            $message = 'Failed for module [' . $key . ']';
            $this->assertEquals($excepted, $this->modules->isEnabled($key), $message);
            $this->assertEquals($excepted, ! $this->modules->isDisabled($key), $message);
        }
    }

    /** @test */
    public function it_can_enable_and_disable_a_module()
    {
        $this->loadModules();

        $this->assertFalse($this->modules->isEnabled('foo'));

        $this->modules->enable('foo');

        $this->assertTrue($this->modules->isEnabled('foo'));

        $this->modules->disable('foo');

        $this->assertFalse($this->modules->isEnabled('foo'));
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\Moduly\Exceptions\ModuleNotFound
     * @expectedExceptionMessage Module not found [hello]
     */
    public function it_must_throw_module_not_found_on_enable()
    {
        $this->loadModules();

        $this->modules->enable('hello');
    }

    /**
     * @test
     *
     * @expectedException        \Arcanedev\Moduly\Exceptions\ModuleNotFound
     * @expectedExceptionMessage Module not found [world]
     */
    public function it_must_throw_module_not_found_on_disable()
    {
        $this->loadModules();

        $this->modules->disable('world');
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    private function loadModules()
    {
        $this->modules->load($this->getModulesFixturesPath());
    }
}
