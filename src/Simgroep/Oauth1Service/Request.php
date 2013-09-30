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
        $header = $this->getAuthorizationHeader();
        $this->header = new Header($header);
    }

    protected function getAuthorizationHeader()
    {
        if (function_exists('apache_request_headers')) {
            $header = apache_request_headers();
        } else {
            $header = $this->parseRequestHeaders();
        }

        if (!isset($header['Authorization'])) {
            throw new Exception('Authorization part of header missing...');
        }

        return $header['Authorization'];
    }

    protected function parseRequestHeaders()
    {
        $headers = array();
        foreach ($_SERVER as $key => $value) {
            if (substr($key, 0, 5) <> 'HTTP_') {
                continue;
            }
            $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $headers[$header] = $value;
        }

        return $headers;
    }

    public function getRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
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
