<?php

namespace Simgroep\Oauth1Service;

class TokenProvider implements TokenProviderInterface
{

    private $error;

    public function __construct($error = false)
    {
        if($error)
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
