<?php namespace Arcanedev\Moduly\Tests;

use Arcanedev\Moduly\ModulyServiceProvider;

/**
 * Class     ModulyServiceProviderTest
 *
 * @package  Arcanedev\Moduly\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ModulyServiceProviderTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var ModulyServiceProvider */
    private $provider;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    public function setUp()
    {
        parent::setUp();

        $this->provider = $this->app->getProvider(ModulyServiceProvider::class);
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->provider);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_get_provides_list()
    {
        $provided = $this->provider->provides();
        $defaults = ['arcanedev.moduly'];
        $this->assertCount(count($defaults), $provided);
        $this->assertEquals($defaults, $provided);
    }
}
