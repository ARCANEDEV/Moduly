<?php namespace Arcanedev\Moduly\Tests;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Class CommandTestCase
 * @package Arcanedev\Moduly\Tests
 */
abstract class CommandTestCase extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->artisan('module:list');
        $this->artisan('module:make', [
            'module' => $this->moduleName
        ]);
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->remove($this->moduleName);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Delete module folder
     *
     * @param string $name
     */
    public function remove($name)
    {
        $dir   = $this->getFixtureModulePath($name);
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
