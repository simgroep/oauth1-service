<?php

interface OauthTokenProviderInterface
{
    public function getSecret($string);
}
