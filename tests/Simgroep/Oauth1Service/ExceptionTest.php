<?php

namespace Simgroep\Oauth1Service;

use PHPUnit\Framework\TestCase;

class ExceptionTest extends TestCase
{
    /**
     * @var \Simgroep\Oauth1Service\Exception
     */
    protected $object;

    protected function setUp(): void
    {
        $this->object = new Exception();
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Exception::__construct
     */
    public function construct()
    {
        $this->assertInstanceOf('\Simgroep\Oauth1Service\Exception', $this->object);
        $this->assertInstanceOf('\Exception', $this->object);
    }
}

