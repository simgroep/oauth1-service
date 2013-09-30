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
        if (defined('PHPUNIT_TESTSUITE')) {
            $header = array();
            $header['Authorization'] =
<<<EOF
OAuth realm="http://simgroep.nl/",
oauth_consumer_key="0685bd9184jfhq22",
oauth_token="ad180jjd733klru7",
oauth_signature_method="HMAC-SHA1",
oauth_signature="3A7XNSlFRQ8GYjjbypt2w5FN4NQ=",
oauth_timestamp="137131200",
oauth_nonce="4572616e48616d6d65724c61686176",
oauth_version="1.0"
EOF;

        } else {
            if (function_exists('apache_request_headers')) {
                $header = apache_request_headers();
            } else {
                $header = $this->parseRequestHeaders();
            }
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
        if (defined('PHPUNIT_TESTSUITE')) {
            return 'GET';
        }
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getRequestUri()
    {
        if (defined('PHPUNIT_TESTSUITE')) {
            return 'http://simgroep.nl';
        }
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
