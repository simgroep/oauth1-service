<?php

namespace Simgroep\Oauth1Service;

class ExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Simgroep\Oauth1Service\Header
     */
    protected $object;

    protected $authenticationHeader;

    protected function setUp()
    {
        $this->object = new Exception();
    }

    protected function tearDown()
    {

    }
    /**
     * @test
     * @covers Simgroep\Oauth1Service\Header::__construct
     * @covers Simgroep\Oauth1Service\Header::explodeIntoParts
     * @covers Simgroep\Oauth1Service\Header::offsetExists
     * @covers Simgroep\Oauth1Service\Header::offsetGet
     */
    public function construct()
    {
        $header = new Header($this->authenticationHeader);
        $this->assertTrue(isset($header['consumer_key']));
        $this->assertEquals('a', $header['consumer_key']);
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Header::explodeIntoParts
     * @expectedException \Simgroep\Oauth1Service\Exception
     */
    public function incorrectHeader()
    {
        $headerString = 'dummy header';
        $header = new Header($headerString);
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Header::offsetSet
     * @expectedException \Simgroep\Oauth1Service\Exception
     */
    public function assignException()
    {
        $header = new Header($this->authenticationHeader);
        $header['test'] = false;
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Header::offsetUnset
     * @expectedException \Simgroep\Oauth1Service\Exception
     */
    public function unassignException()
    {
        $header = new Header($this->authenticationHeader);
        unset($header['test']);
    }
}

