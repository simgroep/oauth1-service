<?php

namespace Simgroep\Oauth1Service;

class ConsumerProvider implements TokenProviderInterface
{

    public function getSecret($string)
    {
        return 'b';
    }
}
