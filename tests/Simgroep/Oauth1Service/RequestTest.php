<?php

namespace Simgroep\Oauth1Service;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Simgroep\Oauth1Service\Request
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new Request();
    }

    protected function tearDown()
    {

    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Request::getAuthorizationHeader
     * @covers Simgroep\Oauth1Service\Request::__construct
     */
    public function newObjectTest()
    {
        $this->assertInstanceOf('Simgroep\Oauth1Service\Request', $this->object);
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Request::getRequestMethod
     */
    public function getRequestMethodTest()
    {
        $this->assertEquals('GET', $this->object->getRequestMethod());
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Request::getRequestParameters
     */
    public function getRequestParametersTest()
    {
        $this->assertInstanceOf('Simgroep\Oauth1Service\Header', $this->object->header);

    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Request::getRequestUri
     */
    public function getRequestUriTest()
    {
        $this->assertEquals($this->object->getRequestUri(), 'http://simgroep.nl');
    }
}
