<?php

namespace Simgroep\Oauth1Service;

class TokenProvider implements TokenProviderInterface
{

    public function getSecret($string)
    {
        return 'd';
    }
}
