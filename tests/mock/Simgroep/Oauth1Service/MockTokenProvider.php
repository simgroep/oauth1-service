<?php

namespace Simgroep\Oauth1Service;

class MockTokenProvider implements TokenProviderInterface
{

    private $error;

    public function __construct($error = false)
    {
        if((bool)$error)
        {
            $this->error = true;
        } else {
            $this->error = false;
        }
    }

    public function getSecret($string)
    {
        if ($this->error) {
            return '';
        }

        return 'd';
    }
}
