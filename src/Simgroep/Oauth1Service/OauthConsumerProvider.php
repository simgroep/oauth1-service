<?php

namespace Simgroep\Oauth1Service;

class ConsumerProvider implements OauthTokenProviderInterface
{

    public function getSecret($string)
    {
        return 'b';
    }
}
