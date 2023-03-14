<?php

namespace Simgroep\Oauth1Service;

use PHPUnit\Framework\TestCase;

class SymfonyRequestTest extends TestCase
{
    /**
     * @var \Simgroep\Oauth1Service\SymfonyRequest
     */
    protected $object;

    protected $testHeader = 'OAuth oauth_consumer_key="a", oauth_nonce="9c7e78fc42a259ee7ec5b600543e2495", oauth_signature="1Qiem0TXjO05aB2Z77YIuCEikMA%3D", oauth_signature_method="HMAC-SHA1", oauth_timestamp="1380103322", oauth_token="c", oauth_version="1.0"';

    protected $request;

    protected $headers;

    protected $query;

    protected $postRequest;

    protected function setUp(): void
    {
        if (! class_exists("Symfony\Component\HttpFoundation\HeaderBag")) {
            $this->markTestSkipped('Symfony\Component\HttpFoundation\HeaderBag class not found');
        }

        $this->headers = $this->getMockBuilder("\\Symfony\\Component\\HttpFoundation\\HeaderBag")
            ->addMethods(['get'])
            ->getMock();
        $this->headers->expects($this->any())
            ->method('get')
            ->will($this->returnValue($this->testHeader));

        $this->query = $this->getMockBuilder("\\Symfony\\Component\\HttpFoundation\\ParameterBag")
            ->addMethods(['all'])
            ->getMock();

        $this->postRequest = $this->getMockBuilder("\\Symfony\\Component\\HttpFoundation\\ParameterBag")
            ->addMethods(['all'])
            ->getMock();

        $this->request = $this->getMockBuilder("\\Symfony\\Component\\HttpFoundation\\Request")
            ->addMethods(['getMethod', 'getRealMethod', 'getSchemeAndHttpHost', 'getRequestUri'])
            ->getMock();

        $this->request->headers = $this->headers;
        $this->request->query = $this->query;
        $this->request->request = $this->postRequest;

        $this->object = new SymfonyRequest($this->request);
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\SymfonyRequest::__construct
     */
    public function construct()
    {
        $this->assertInstanceOf('Simgroep\Oauth1Service\Request', $this->object);
        $this->assertInstanceOf('Simgroep\Oauth1Service\Header', $this->object->header);
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\SymfonyRequest::getRequestMethod
     */
    public function requestMethodGet()
    {
        $this->request->expects($this->any())
            ->method('getMethod')
            ->will($this->returnValue('GET'));

        $this->assertEquals('GET', $this->object->getRequestMethod());
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\SymfonyRequest::getRequestMethod
     */
    public function requestMethodPost()
    {
        $this->request->expects($this->any())
            ->method('getMethod')
            ->will($this->returnValue('POST'));

        $this->assertEquals('POST', $this->object->getRequestMethod());
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\SymfonyRequest::getRequestParameters
     */
    public function requestParamsGet()
    {
        $this->request->expects($this->any())
            ->method('getRealMethod')
            ->will($this->returnValue('GET'));

        $this->query->expects($this->any())
            ->method('all')
            ->will($this->returnValue(array('foo' => 'bar')));

        $this->assertEquals($this->object->getRequestParameters(), array('foo' => 'bar'));
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\SymfonyRequest::getRequestParameters
     */
    public function requestParamsPost()
    {
        $this->request->expects($this->any())
            ->method('getRealMethod')
            ->will($this->returnValue('POST'));

        $this->postRequest->expects($this->any())
            ->method('all')
            ->will($this->returnValue(array('foo' => 'bar')));

        $this->assertEquals($this->object->getRequestParameters(), array('foo' => 'bar'));
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\SymfonyRequest::getRequestUri
     */
    public function requestUri()
    {
        $this->request->expects($this->any())
            ->method('getSchemeAndHttpHost')
            ->will($this->returnValue('http://example.org'));

        $this->request->expects($this->any())
            ->method('getRequestUri')
            ->will($this->returnValue('/test'));

        $this->assertEquals($this->object->getRequestUri(), 'http://example.org/test');
    }
}

