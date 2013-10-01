<?php

namespace Simgroep\Oauth1Service;

class TokenProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Simgroep\Oauth1Service\TokenProvider
     */
    protected $object;
    protected $objectWithError;

    protected function setUp()
    {
        $this->object = new TokenProvider();
        $this->objectWithError = new MockTokenProvider(true);
    }

    protected function tearDown()
    {

    }

    /**
     * @test
     */
    public function testClass()
    {
        $this->assertInstanceOf('Simgroep\Oauth1Service\TokenProvider', $this->object);
    }

    /**
     * @test
     */
    public function getSecredTest()
    {
        $this->assertEquals('d', $this->object->getSecret('some string'));
        $this->assertEmpty($this->objectWithError->getSecret('some string'));
    }
}