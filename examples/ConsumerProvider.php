<?php

use Simgroep\Oauth1Service\TokenProviderInterface;

class ConsumerProvider implements TokenProviderInterface
{

    public function getSecret($string)
    {
        return 'consumerSecret';
    }
}
