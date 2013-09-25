<?php

namespace Simgroep\Oauth1Service;

class Request
{

    public function __construct()
    {

    }

    public function getRequestMethod()
    {
        return 'GET';
    }

    public function getRequestUri()
    {
        return 'http://192.168.2.222/index_dev.php/simsite/documents';
    }

    public function getRequestParameters()
    {
        return array();
    }

}
