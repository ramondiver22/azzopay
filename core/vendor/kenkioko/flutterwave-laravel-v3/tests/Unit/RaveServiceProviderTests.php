<?php

namespace Tests\Unit;

use Tests\TestCase;
use Laravel\Flutterwave\Rave;

class RaveServiceProviderTests extends TestCase
{
    /**
     * Tests if service provider Binds alias "laravelrave" to \Laravel\Flutterwave\Rave
     *
     * @test
     */
    public function isBound()
    {
        $this->assertTrue($this->app->bound('laravelrave'));
    }
    /**
     * Test if service provider returns \Rave as alias for \Laravel\Flutterwave\Rave
     *
     * @test
     */
    public function hasAliased()
    {
        $this->assertTrue($this->app->isAlias("Laravel\Flutterwave\Rave"));
        $this->assertEquals('laravelrave', $this->app->getAlias("Laravel\Flutterwave\Rave"));
    }
}
