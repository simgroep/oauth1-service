<?php

namespace Simgroep\Oauth1Service;

interface TokenProviderInterface
{
    public function getSecret($string);
}

