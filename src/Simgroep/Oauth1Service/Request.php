<?php

namespace Simgroep\Oauth1Service;

use Simgroep\Oauth1Service\Header;

class Request
{
    /**
     * @var OAuthHeader
     */
    public $header;

    public function __construct()
    {
        $this->header = new Header();
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function getRequestMethod()
    {
        return 'GET';
    }

    public function getRequestUri()
    {
        //@todo uri for test
        $uri = 'http://' .
                htmlentities($_SERVER['SERVER_NAME'], ENT_QUOTES) .
                htmlentities($_SERVER['REQUEST_URI'], ENT_QUOTES);

        return str_replace('?', '', $uri);
    }

    public function getRequestParameters()
    {
        return array();
    }

}
