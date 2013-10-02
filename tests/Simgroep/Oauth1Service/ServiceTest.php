<?php

namespace Simgroep\Oauth1Service;

class ServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Simgroep\Oauth1Service\Service
     */
    protected $object;

    protected $request;

    protected $consumerProvider;

    protected $tokenProvider;

    protected function setUp()
    {
        $this->request = $this->getMockBuilder("\\Simgroep\\Oauth1Service\\Request")->disableOriginalConstructor()->getMock();
        $this->request->header = $this->getMockBuilder("\\Simgroep\\Oauth1Service\\Header")->disableOriginalConstructor()->getMock();
        $this->consumerProvider = $this->getMock("\\Simgroep\\Oauth1Service\\TokenProviderInterface");
        $this->tokenProvider = $this->getMock("\\Simgroep\\Oauth1Service\\TokenProviderInterface");
        $this->object = new Service($this->request, $this->consumerProvider, $this->tokenProvider);
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Service::__construct
     */
    public function construct()
    {
        $this->assertInstanceOf('Simgroep\Oauth1Service\Service', $this->object);
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Service::setAccessTokenRequired
     * @covers Simgroep\Oauth1Service\Service::getAccessTokenRequired
     */
    public function accessTokenRequiredDefault()
    {
        $this->assertTrue($this->object->getAccessTokenRequired());
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Service::setAccessTokenRequired
     * @covers Simgroep\Oauth1Service\Service::getAccessTokenRequired
     */
    public function accessTokenRequiredTrue()
    {
        $this->object->setAccessTokenRequired(true);
        $this->assertTrue($this->object->getAccessTokenRequired());
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Service::setAccessTokenRequired
     * @covers Simgroep\Oauth1Service\Service::getAccessTokenRequired
     */
    public function accessTokenRequiredFalse()
    {
        $this->object->setAccessTokenRequired(false);
        $this->assertFalse($this->object->getAccessTokenRequired());
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Service::isValidRequest
     * @covers Simgroep\Oauth1Service\Service::getError
     */
    public function inValidRequestVersion()
    {
        $this->request->header->expects($this->any())
            ->method('offsetGet')
            ->will($this->returnValue('0.1'));

        $this->assertFalse($this->object->isValidRequest());
        $this->assertEquals('Wrong version.', $this->object->getError());
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Service::isValidRequest
     */
    public function inValidRequestSignatureMethod()
    {
        $this->request->header->expects($this->any())
            ->method('offsetGet')
            ->will($this->onConsecutiveCalls('1.0', 'md5'));

        $this->assertFalse($this->object->isValidRequest());
        $this->assertEquals('Wrong hashing method.', $this->object->getError());
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Service::isValidRequest
     */
    public function inValidRequestMissingConsumerToken()
    {
        $this->request->header->expects($this->any())
            ->method('offsetGet')
            ->will(
                $this->returnCallback(
                    function ($key) {
                        $returnValues = array(
                            'version' => '1.0',
                            'signature_method' => 'HMAC-SHA1',
                            'signature' => '',
                            'consumer_key' => '',
                            'token' => 'tokenKey',
                            'nonce' => '9c7e78fc42a259ee7ec5b600543e2495',
                            'timestamp' => '1234567890',
                        );
                        return $returnValues[$key];
                    }
                )
            );


        $this->consumerProvider->expects($this->any())
            ->method('getSecret')
            ->will($this->returnValue(''));


        $this->assertFalse($this->object->isValidRequest());
        $this->assertEquals('Consumer token missing.', $this->object->getError());
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Service::isValidRequest
     */
    public function inValidRequestConsumerToken()
    {
        $this->request->header->expects($this->any())
            ->method('offsetGet')
            ->will(
                $this->returnCallback(
                    function ($key) {
                        $returnValues = array(
                            'version' => '1.0',
                            'signature_method' => 'HMAC-SHA1',
                            'signature' => '',
                            'consumer_key' => 'consumerKey',
                            'token' => 'tokenKey',
                            'nonce' => '9c7e78fc42a259ee7ec5b600543e2495',
                            'timestamp' => '1234567890',
                        );
                        return $returnValues[$key];
                    }
                )
            );

        $this->consumerProvider->expects($this->any())
            ->method('getSecret')
            ->will($this->returnValue(''));

        $this->assertFalse($this->object->isValidRequest());
        $this->assertEquals('Consumer token unknown.', $this->object->getError());
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Service::isValidRequest
     */
    public function inValidRequestAccessToken()
    {
        $this->request->header->expects($this->any())
            ->method('offsetGet')
            ->will(
                $this->returnCallback(
                    function ($key) {
                        $returnValues = array(
                            'version' => '1.0',
                            'signature_method' => 'HMAC-SHA1',
                            'signature' => '',
                            'consumer_key' => 'consumerKey',
                            'token' => 'tokenKey',
                            'nonce' => '9c7e78fc42a259ee7ec5b600543e2495',
                            'timestamp' => '1234567890',
                        );
                        return $returnValues[$key];
                    }
                )
            );

        $this->consumerProvider->expects($this->any())
            ->method('getSecret')
            ->will($this->returnValue('something'));

        $this->tokenProvider->expects($this->any())
            ->method('getSecret')
            ->will($this->returnValue(''));

        $this->assertFalse($this->object->isValidRequest());
        $this->assertEquals('Access token unknown.', $this->object->getError());
    }

    /**
     * The request should fail because no accessToken has been provided.
     *
     * @test
     * @covers Simgroep\Oauth1Service\Service::isValidRequest
     * @covers Simgroep\Oauth1Service\Service::buildSignature
     * @covers Simgroep\Oauth1Service\Service::getDetails
     */
    public function inValidRequestMissingRequiredAccessToken()
    {
        $this->request->expects($this->any())
            ->method('getRequestParameters')
            ->will($this->returnValue(array('foo' => 'bar')));

        $this->request->expects($this->any())
            ->method('getRequestMethod')
            ->will($this->returnValue('GET'));

        $this->request->expects($this->any())
            ->method('getRequestUri')
            ->will($this->returnValue('http://example.org/test'));

        $this->request->header->expects($this->any())
            ->method('offsetGet')
            ->will(
                $this->returnCallback(
                    function ($key) {
                        $returnValues = array(
                            'version' => '1.0',
                            'signature_method' => 'HMAC-SHA1',
                            'signature' => '7mA56+DuwfTQwWExdBQDaE2EwH4=',
                            'consumer_key' => 'consumerKey',
                            'token' => '',
                            'nonce' => '9c7e78fc42a259ee7ec5b600543e2495',
                            'timestamp' => '1234567890',
                        );
                        return $returnValues[$key];
                    }
                )
            );

        $this->consumerProvider->expects($this->any())
            ->method('getSecret')
            ->will($this->returnValue('consumerSecret'));

        $this->tokenProvider->expects($this->any())
            ->method('getSecret')
            ->will($this->returnValue('tokenSecret'));

        $this->object->setAccessTokenRequired(true);

        $this->assertFalse($this->object->isValidRequest());
        $this->assertEquals('Access token missing.', $this->object->getError());
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Service::isValidRequest
     * @covers Simgroep\Oauth1Service\Service::buildSignature
     */
    public function inValidRequestSignature()
    {
        $this->request->expects($this->any())
            ->method('getRequestParameters')
            ->will($this->returnValue(array()));

        $this->request->header->expects($this->any())
            ->method('offsetGet')
            ->will(
                $this->returnCallback(
                    function ($key) {
                        $returnValues = array(
                            'version' => '1.0',
                            'signature_method' => 'HMAC-SHA1',
                            'signature' => '7mA56+DuwfTQwWExdBQDaE2EwH4=',
                            'consumer_key' => 'consumerKey',
                            'token' => 'tokenKey',
                            'nonce' => '9c7e78fc42a259ee7ec5b600543e2495',
                            'timestamp' => '1234567890',
                        );
                        return $returnValues[$key];
                    }
                )
            );

        $this->consumerProvider->expects($this->any())
            ->method('getSecret')
            ->will($this->returnValue('something'));

        $this->tokenProvider->expects($this->any())
            ->method('getSecret')
            ->will($this->returnValue('something'));

        $this->assertFalse($this->object->isValidRequest());
        $this->assertEquals('Incorrect signature.', $this->object->getError());
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Service::isValidRequest
     * @covers Simgroep\Oauth1Service\Service::buildSignature
     * @covers Simgroep\Oauth1Service\Service::getDetails
     */
    public function validRequestSignature()
    {
        $this->request->expects($this->any())
            ->method('getRequestParameters')
            ->will($this->returnValue(array('foo' => 'bar')));

        $this->request->expects($this->any())
            ->method('getRequestMethod')
            ->will($this->returnValue('GET'));

        $this->request->expects($this->any())
            ->method('getRequestUri')
            ->will($this->returnValue('http://example.org/test'));

        $this->request->header->expects($this->any())
            ->method('offsetGet')
            ->will(
                $this->returnCallback(
                    function ($key) {
                        $returnValues = array(
                            'version' => '1.0',
                            'signature_method' => 'HMAC-SHA1',
                            'signature' => '+Z7LDrAIx9vXwW/rH2ugdOg0Es0=',
                            'consumer_key' => 'consumerKey',
                            'token' => 'tokenKey',
                            'nonce' => '9c7e78fc42a259ee7ec5b600543e2495',
                            'timestamp' => '1234567890',
                        );
                        return $returnValues[$key];
                    }
                )
            );

        $this->consumerProvider->expects($this->any())
            ->method('getSecret')
            ->will($this->returnValue('consumerSecret'));

        $this->tokenProvider->expects($this->any())
            ->method('getSecret')
            ->will($this->returnValue('tokenSecret'));

        $this->assertTrue($this->object->isValidRequest());
        $this->assertEquals('Unknown error.', $this->object->getError());
        $details = $this->object->getDetails();
        $this->assertArrayHasKey('consumerToken', $details);
        $this->assertArrayHasKey('accessToken', $details);
        $this->assertEquals('consumerKey', $details['consumerToken']);
        $this->assertEquals('tokenKey', $details['accessToken']);
    }

    /**
     * Everything should succeed, even though there is no accessToken.
     *
     * @test
     * @covers Simgroep\Oauth1Service\Service::isValidRequest
     * @covers Simgroep\Oauth1Service\Service::buildSignature
     * @covers Simgroep\Oauth1Service\Service::getDetails
     */
    public function validRequestSignatureWithMissingOptionalAccessToken()
    {
        $this->request->expects($this->any())
            ->method('getRequestParameters')
            ->will($this->returnValue(array('foo' => 'bar')));

        $this->request->expects($this->any())
            ->method('getRequestMethod')
            ->will($this->returnValue('GET'));

        $this->request->expects($this->any())
            ->method('getRequestUri')
            ->will($this->returnValue('http://example.org/test'));

        $this->request->header->expects($this->any())
            ->method('offsetGet')
            ->will(
                $this->returnCallback(
                    function ($key) {
                        $returnValues = array(
                            'version' => '1.0',
                            'signature_method' => 'HMAC-SHA1',
                            'signature' => '7mA56+DuwfTQwWExdBQDaE2EwH4=',
                            'consumer_key' => 'consumerKey',
                            'token' => '',
                            'nonce' => '9c7e78fc42a259ee7ec5b600543e2495',
                            'timestamp' => '1234567890',
                        );
                        return $returnValues[$key];
                    }
                )
            );

        $this->consumerProvider->expects($this->any())
            ->method('getSecret')
            ->will($this->returnValue('consumerSecret'));

        $this->object->setAccessTokenRequired(false);

        $this->assertTrue($this->object->isValidRequest());
        $this->assertEquals('Unknown error.', $this->object->getError());
        $details = $this->object->getDetails();
        $this->assertArrayHasKey('consumerToken', $details);
        $this->assertArrayHasKey('accessToken', $details);
        $this->assertEquals('consumerKey', $details['consumerToken']);
        $this->assertEquals('', $details['accessToken']);
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Service::getError
     */
    public function getErrorTest()
    {
        $this->assertEquals('Unknown error.', $this->object->getError());
    }
}

