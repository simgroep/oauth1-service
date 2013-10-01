<?php

namespace Simgroep\Oauth1Service;

class RequestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Simgroep\Oauth1Service\Request
     */
    protected $object;

    protected $testHeader = 'OAuth oauth_consumer_key="a", oauth_nonce="9c7e78fc42a259ee7ec5b600543e2495", oauth_signature="1Qiem0TXjO05aB2Z77YIuCEikMA%3D", oauth_signature_method="HMAC-SHA1", oauth_timestamp="1380103322", oauth_token="c", oauth_version="1.0"';

    protected function setUp()
    {
        $_SERVER['HTTP_AUTHORIZATION'] = $this->testHeader;
        $this->object = new Request();
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Request::getAuthorizationHeader
     * @covers Simgroep\Oauth1Service\Request::__construct
     */
    public function construct()
    {
        $this->assertInstanceOf('Simgroep\Oauth1Service\Request', $this->object);
        $this->assertInstanceOf('Simgroep\Oauth1Service\Header', $this->object->header);
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Request::getAuthorizationHeader
     * @covers Simgroep\Oauth1Service\Request::__construct
     * @expectedException \Simgroep\Oauth1Service\Exception
     */
    public function constructWithoutHeader()
    {
        unset($_SERVER['HTTP_AUTHORIZATION']);
        $this->object = new Request();
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Request::getRequestMethod
     */
    public function requestMethodGet()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertEquals('GET', $this->object->getRequestMethod());
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Request::getRequestMethod
     */
    public function requestMethodPost()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertEquals('POST', $this->object->getRequestMethod());
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Request::getRequestParameters
     */
    public function requestParamsGet()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['foo'] = 'bar';
        $this->assertEquals($this->object->getRequestParameters(), array('foo' => 'bar'));
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Request::getRequestParameters
     */
    public function requestParamsPost()
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['foo'] = 'bar';
        $this->assertEquals($this->object->getRequestParameters(), array('foo' => 'bar'));
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Request::getRequestUri
     */
    public function getRequestUriHttp()
    {
        $_SERVER['HTTP_HOST'] = 'example.org';
        $_SERVER['REQUEST_URI'] = '/test';
        $this->assertEquals($this->object->getRequestUri(), 'http://example.org/test');
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Request::getRequestUri
     */
    public function getRequestUriHttps()
    {
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['HTTP_HOST'] = 'example.org';
        $_SERVER['REQUEST_URI'] = '/test';
        $this->assertEquals($this->object->getRequestUri(), 'https://example.orgl/test');
    }
}

