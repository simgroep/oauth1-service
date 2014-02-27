<?php

namespace Simgroep\Oauth1Service;

use Simgroep\Oauth1Service\Header;

/**
 * Basic request class
 *
 * Uses readily available superglobals to provide context values.
 *
 * @package Simgroep\Oauth1Service
 */
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
        if (array_key_exists('HTTP_AUTHORIZATION', $_SERVER)) {
            return $_SERVER['HTTP_AUTHORIZATION'];
        } else {
            throw new Exception('No Authorization signature in request.');
        }
    }

    public function getRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getRequestUri()
    {
        if (empty($_SERVER['HTTPS'])) {
            $scheme = 'http://';
        } else {
            $scheme = 'https://';
        }

        $host = htmlentities($_SERVER['HTTP_HOST'], ENT_QUOTES);
        $path = htmlentities($_SERVER['REQUEST_URI'], ENT_QUOTES);

        return strtok($scheme . $host . $path, '?');
    }

    public function getRequestParameters()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $_POST;
        } else {
            return $_GET;
        }
    }
}

