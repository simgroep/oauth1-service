<?php

namespace Simgroep\Oauth1Service;

class HeaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Simgroep\Oauth1Service\Header
     */
    protected $object;

    protected function setUp()
    {
//        parent::setUp();
        ob_start();

        $headerString = 'OAuth oauth_consumer_key="a", oauth_nonce="9c7e78fc42a259ee7ec5b600543e2495", oauth_signature="1Qiem0TXjO05aB2Z77YIuCEikMA%3D", oauth_signature_method="HMAC-SHA1", oauth_timestamp="1380103322", oauth_token="c", oauth_version="1.0"';
        $_SERVER['HTTP_Authorization'] = $headerString;

    }

    protected function tearDown()
    {
        unset($_SERVER['HTTP_Authorization']);
        ob_clean();
//        parent::tearDown();
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
        $header = new Header();
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
        $_SERVER['HTTP_Authorization'] = $headerString;
        $header = new Header();
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Header::offsetSet
     * @expectedException \Simgroep\Oauth1Service\Exception
     */
    public function assignException()
    {
        $header = new Header();
        $header['test'] = false;
    }

    /**
     * @test
     * @covers Simgroep\Oauth1Service\Header::offsetUnset
     * @expectedException \Simgroep\Oauth1Service\Exception
     */
    public function unassignException()
    {
        $header = new Header();
        unset($header['test']);
    }
}

