<?php namespace Arcanedev\Moduly\Tests\Repositories;

use Arcanedev\Moduly\Repositories\Module;
use Arcanedev\Moduly\Tests\TestCase;
use Illuminate\Config\Repository as Config;
use Illuminate\Filesystem\Filesystem;

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
    /** @var Module */
    protected $module;

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

        $this->module = new Module(
            $this->config,
            $this->files
        );
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
        $this->assertCount(0, $this->module->all());
    }

    /** @test */
    public function it_can_set_and_get_path()
    {
        $path = $this->config->get('moduly.path');

        $this->assertEquals($path, $this->module->getPath());

        $this->module->setPath($path = __DIR__ . '/../fixture/modules');

        $this->assertEquals($path, $this->module->getPath());
    }

    /** @test */
    public function it_can_get_module_path()
    {
        $this->assertEquals(
            $this->config->get('moduly.path') . "/{$this->moduleName}/",
            $this->module->getModulePath($this->moduleName)
        );
    }

    /** @test */
    public function it_can_get_modules_namespace()
    {
        $this->assertEquals(
            $this->config->get('moduly.namespace'),
            $this->module->getNamespace()
        );
    }

    /** @test */
    public function it_can_get_ignored()
    {
        $this->assertEquals(
            $this->config->get('moduly.ignored'),
            $this->module->getIgnored()
        );
    }
}
