<?php namespace Arcanedev\Moduly\Tests;

use Arcanedev\Moduly\Facades\Moduly as ModulyFacade;
use Arcanedev\Moduly\ModulyServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Class     TestCase
 *
 * @package  Arcanedev\Moduly\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class TestCase extends BaseTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @var string
     */
    protected $moduleName = 'dummy';

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->artisan('migrate:install');
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Package Functions
     | ------------------------------------------------------------------------------------------------
     */
    protected function getPackageProviders($app)
    {
        return [
            ModulyServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Moduly' => ModulyFacade::class
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['path.base'] = realpath(__DIR__ . '/../');

        $app['config']->set('app.debug', true);
        $app['config']->set('moduly.path', $this->getModulesFixturesPath());
        $app['config']->set('moduly.namespace', 'Arcanedev\\');

        $app['config']->set('database.default', 'db-test');
        $app['config']->set('database.connections.db-test', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get Moduly
     *
     * @return \Arcanedev\Moduly\Moduly
     */
    public function moduly()
    {
        return $this->app['arcanedev.moduly'];
    }

    /**
     * Check if module is enabled
     *
     * @return bool
     */
    protected function isEnabled()
    {
        return $this->moduly()->isEnabled($this->moduleName);
    }

    /**
     * Get fixtures folder path
     *
     * @return string
     */
    protected function getFixturesPath()
    {
        return realpath(__DIR__ . '/fixtures');
    }

    /**
     * Get modules fixtures folder path
     *
     * @return string
     */
    protected function getModulesFixturesPath()
    {
        return realpath($this->getFixturesPath() . '/modules');
    }

    /**
     * Get module fixture folder path by name
     *
     * @return string
     */
    protected function getModulePath($name)
    {
        return realpath($this->getModulesFixturesPath() . '/' . $name);
    }

    /**
     * Create Module
     *
     * @param string  $name
     */
    protected function makeModule($name)
    {
        $this->artisan('module:make', [
            'module' => $name
        ]);
    }

    /**
     * Delete module folder
     *
     * @param string  $name
     */
    protected function deleteModule($name)
    {
        $dir   = $this->getModulePath($name);
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileInfo) {
            $todo = ($fileInfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileInfo->getRealPath());
        }

        rmdir($dir);
    }
}
