<?php namespace Arcanedev\Moduly\Tests;

use Arcanedev\Moduly\Moduly;

/**
 * Class ModulyTest
 * @package Arcanedev\Moduly\Tests
 */
class ModulyTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @var Moduly
     */
    protected $moduly;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->moduly  = $this->moduly();
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->moduly);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(Moduly::class, $this->moduly);
    }
}
