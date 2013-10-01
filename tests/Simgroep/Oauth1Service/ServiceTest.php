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
        $this->object = new Service(new Request, new MockTokenProvider, new MockTokenProvider);
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
     * @covers Simgroep\Oauth1Service\Service::buildSignature
     */
    public function isValidRequestTest()
    {
        $requestOK = new Request();
        $service = new Service($requestOK, new MockTokenProvider, new MockTokenProvider);
        $this->assertTrue($service->isValidRequest());
        unset($service);

        $requestWrongVersion = new Request('version');
        $service = new Service($requestWrongVersion, new MockTokenProvider, new MockTokenProvider);
        $this->assertFalse($service->isValidRequest());
        unset($service);

        $requestWrongHash = new Request('hash');
        $service = new Service($requestWrongHash, new MockTokenProvider, new MockTokenProvider);
        $this->assertFalse($service->isValidRequest());
        unset($service);

        $tokenProvider = new MockTokenProvider();
        $tokenProviderError = new MockTokenProvider(true);

        $requestWrongConsumerTonken = new Request('consumer_token');
        $service = new Service($requestWrongConsumerTonken, $tokenProvider, $tokenProviderError);
        $this->assertFalse($service->isValidRequest());
        unset($service);

        $requestWrongAcessTonken = new Request('access_token');
        $service = new Service($requestWrongAcessTonken, $tokenProviderError, $tokenProvider);
        $this->assertFalse($service->isValidRequest());
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
