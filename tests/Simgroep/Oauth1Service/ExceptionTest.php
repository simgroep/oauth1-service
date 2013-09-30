<?php

namespace Simgroep\Oauth1Service;

class ExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Simgroep\Oauth1Service\Exception
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new Exception();
    }

    protected function tearDown()
    {

    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Exception::__construct
     */
    public function __constructTest()
    {
        $this->assertInstanceOf('\Simgroep\Oauth1Service\Exception', $this->object);
    }
}

