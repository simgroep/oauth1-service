<?php

class OauthTokenProvider implements OauthTokenProviderInterface
{

    public function getSecret($string)
    {
        return 'd';
    }
}
