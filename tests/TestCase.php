<?php namespace Arcanedev\Moduly\Tests;

use Arcanedev\Moduly\ModulyServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

/**
 * Class TestCase
 * @package Arcanedev\Moduly\Tests
 */
abstract class TestCase extends BaseTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
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
            'Moduly' => \Arcanedev\Moduly\Facades\Module::class
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
        $app['config']->set('moduly.path', __DIR__ . '/fixture/modules');
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
     * Get fixture module path
     *
     * @param  string $name
     *
     * @return string
     */
    public function getFixtureModulePath($name)
    {
        return realpath(__DIR__ . '/fixture/modules/' . $name);
    }
}
