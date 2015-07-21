<?php namespace Arcanedev\Moduly\Tests\Repositories;

use Arcanedev\Moduly\Repositories\Modules;
use Arcanedev\Moduly\Tests\TestCase;
use Illuminate\Config\Repository as Config;
use Illuminate\Filesystem\Filesystem;

/**
 * Class ModulesTest
 * @package Arcanedev\Moduly\Tests\Repositories
 */
class ModulesTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var Modules */
    protected $modules;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Filesystem
     */
    protected $files;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->config = $this->app['config'];
        $this->files  = $this->app['files'];

        $this->modules = new Modules(
            $this->config,
            $this->files
        );
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
        $this->assertInstanceOf(Modules::class, $this->modules);
        $this->assertEquals(0, $this->modules->count());
        $this->assertCount(0, $this->modules->all());
    }

    /** @test */
    public function it_can_set_and_get_path()
    {
        $path = $this->config->get('moduly.path');

        $this->assertEquals($path, $this->modules->getPath());

        $this->modules->setPath($path = __DIR__ . '/../fixture/modules');

        $this->assertEquals($path, $this->modules->getPath());
    }

    /** @test */
    public function it_can_get_module_path()
    {
        $this->assertEquals(
            $this->config->get('moduly.path') . "/{$this->moduleName}/",
            $this->modules->getModulePath($this->moduleName)
        );
    }

    /** @test */
    public function it_can_get_modules_namespace()
    {
        $this->assertEquals(
            $this->config->get('moduly.namespace'),
            $this->modules->getNamespace()
        );
    }

    /** @test */
    public function it_can_get_ignored()
    {
        $this->assertEquals(
            $this->config->get('moduly.ignored'),
            $this->modules->getIgnored()
        );
    }

    /** @test */
    public function it_can_get_all_modules()
    {
        $this->createModule($this->moduleName);

        $modules = $this->modules->all();
        $this->assertCount(1, $modules);
        $this->assertEquals(1, $modules->count());

        $this->deleteModule($this->moduleName);
    }

    /** @test */
    public function it_can_get_one_module()
    {
        $this->createModule($this->moduleName);

        $module = $this->modules->all()->first();
        $name   = ucfirst($this->moduleName);

        $this->assertEquals($name, $module['name']);
        $this->assertEquals($this->moduleName, $module['slug']);
        $this->assertTrue($module['enabled']);
        $this->assertEquals("This is the description for the $name module.", $module['description']);
        $this->assertEquals('1.0.0', $module['version']);
        $this->assertEquals(9001, $module['order']);

        $this->deleteModule($this->moduleName);
    }
}
