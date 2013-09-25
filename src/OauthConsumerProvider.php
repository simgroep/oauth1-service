<?php

class OauthConsumerProvider implements OauthTokenProviderInterface
{

    public function getSecret($string)
    {
        return 'b';
    }
}
