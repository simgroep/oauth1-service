<?php

namespace Simgroep\Oauth1Service;

use PHPUnit\Framework\TestCase;

class Zf1RequestTest extends TestCase
{
    /**
     * @var \Simgroep\Oauth1Service\Zf1Request
     */
    protected $object;

    protected $testHeader = 'OAuth oauth_consumer_key="a", oauth_nonce="9c7e78fc42a259ee7ec5b600543e2495", oauth_signature="1Qiem0TXjO05aB2Z77YIuCEikMA%3D", oauth_signature_method="HMAC-SHA1", oauth_timestamp="1380103322", oauth_token="c", oauth_version="1.0"';

    protected $request;

    protected function setUp(): void
    {
        if (! class_exists("Zend_Controller_Request_Http")) {
            $this->markTestSkipped('Zend_Controller_Request_Http class not found');
        }

        $this->request = $this->getMockBuilder("\\Zend_Controller_Request_Http")
            ->addMethods([
                'getHeader',
                'getMethod',
                'getScheme',
                'getHttpHost',
                'getRequestUri',
                'isPost',
                'getPost',
                'getQuery'
            ])->getMock();

        $this->request->expects($this->any())
            ->method('getHeader')
            ->will($this->returnValue($this->testHeader));

        $this->object = new Zf1Request($this->request);
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Zf1Request::__construct
     */
    public function construct()
    {
        $this->assertInstanceOf('Simgroep\Oauth1Service\Request', $this->object);
        $this->assertInstanceOf('Simgroep\Oauth1Service\Header', $this->object->header);
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Zf1Request::getRequestMethod
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
     * @covers Simgroep\Oauth1Service\Zf1Request::getRequestMethod
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
     * @covers Simgroep\Oauth1Service\Zf1Request::getRequestParameters
     */
    public function requestParamsGet()
    {
        $this->request->expects($this->any())
            ->method('isPost')
            ->will($this->returnValue(false));

        $this->request->expects($this->any())
            ->method('getQuery')
            ->will($this->returnValue(array('foo' => 'bar')));

        $this->assertEquals($this->object->getRequestParameters(), array('foo' => 'bar'));
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Zf1Request::getRequestParameters
     */
    public function requestParamsPost()
    {
        $this->request->expects($this->any())
            ->method('isPost')
            ->will($this->returnValue(true));

        $this->request->expects($this->any())
            ->method('getPost')
            ->will($this->returnValue(array('foo' => 'bar')));

        $this->assertEquals($this->object->getRequestParameters(), array('foo' => 'bar'));
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Zf1Request::getRequestUri
     */
    public function requestUri()
    {
        $this->request->expects($this->any())
            ->method('getScheme')
            ->will($this->returnValue('http'));

        $this->request->expects($this->any())
            ->method('getHttpHost')
            ->will($this->returnValue('example.org'));

        $this->request->expects($this->any())
            ->method('getRequestUri')
            ->will($this->returnValue('/test'));

        $this->assertEquals($this->object->getRequestUri(), 'http://example.org/test');
    }
}

