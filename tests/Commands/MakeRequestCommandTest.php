<?php namespace Arcanedev\Moduly\Tests\Commands;

use Arcanedev\Moduly\Tests\CommandTestCase;

/**
 * Class MakeRequestCommandTest
 * @package Arcanedev\Moduly\Tests\Commands
 */
class MakeRequestCommandTest extends CommandTestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_make_request()
    {
        $this->artisan('module:make-request', [
            'module' => $this->moduleName,
            'name'   => 'CreateFooRequest'
        ]);
    }

    /** @test */
    public function it_cant_make_request_if_module_not_found()
    {
        $this->artisan('module:make-request', [
            'module' => 'bar',
            'name'   => 'CreateFooRequest'
        ]);
    }
}
