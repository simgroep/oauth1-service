<?php

namespace Simgroep\Oauth1Service;

class TokenProvider implements OauthTokenProviderInterface
{

    public function getSecret($string)
    {
        return 'd';
    }
}
