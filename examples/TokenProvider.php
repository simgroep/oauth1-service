<?php

use Simgroep\Oauth1Service\TokenProviderInterface;

class TokenProvider implements TokenProviderInterface
{

    public function getSecret($string)
    {
        return 'tokenSecret';
    }
}
