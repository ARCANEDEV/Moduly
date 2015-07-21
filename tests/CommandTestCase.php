<?php namespace Arcanedev\Moduly\Tests;

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
        $this->createModule($this->moduleName);
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->deleteModule($this->moduleName);
    }
}
