<?php

namespace Simgroep\Oauth1Service;

class ServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Simgroep\Oauth1Service\Service
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new Service(new Request, new TokenProvider, new TokenProvider);
    }

    protected function tearDown()
    {

    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Service::__construct
     */
    public function testClass()
    {
        $this->assertInstanceOf('Simgroep\Oauth1Service\Service', $this->object);
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Service::isValidRequest
     */
    public function isValidRequestTest()
    {
        $this->assertTrue($this->object->isValidRequest());
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Service::getError
     */
    public function getErrorTest()
    {
        $this->assertEquals('Unknown error.', $this->object->getError());
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Service::getDetails
     */
    public function getDetailsTest()
    {
        $details = $this->object->getDetails();

        $this->assertEquals('0685bd9184jfhq22', $details['consumerToken']);
        $this->assertEquals('ad180jjd733klru7', $details['accessToken']);
    }
}
